<?php

namespace App\Http\Controllers;

use App\Models\Master\JenisMagangModel;
use App\Models\Master\KegiatanPerusahaanModel;
use App\Models\Master\PeriodeModel;
use App\Models\Master\PerusahaanModel;
use App\Models\Master\ProdiModel;
use App\Models\Master\TipeKegiatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'KEGIATAN';
        $this->menuUrl   = url('kegiatan');     // set URL untuk menu ini
        $this->menuTitle = 'Kegiatan';                       // set nama menu
        $this->viewPath  = 'master.kegiatan.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Data Master', 'Kegiatan']
        ];

        $activeMenu = [
            'l1' => 'kegiatan',
            'l2' => null,
            'l3' => null
        ];

        $page = [
            'url' => $this->menuUrl,
            'title' => 'Daftar ' . $this->menuTitle
        ];

        return view($this->viewPath . 'index')
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('page', (object) $page)
            ->with('allowAccess', $this->authAccessKey())
            ->with('koordinator', false);
    }

    public function list(Request $request)
    {
        $this->authAction('read', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $auth = auth()->user();
        $id_perusahaan = PerusahaanModel::where('user_id', $auth->user_id)->first()->perusahaan_id;

        $data = KegiatanPerusahaanModel::with('tipe_kegiatan')
            ->where('perusahaan_id', $id_perusahaan)
            ->get();

        //durasi = (akhir_kegiatan - mulai_kegiatan) / (60 * 60 * 24 * 30)
        $data = $data->map(function ($item) {
            $item->periode_kegiatan = (strtotime($item->akhir_kegiatan) - strtotime($item->mulai_kegiatan)) / (60 * 60 * 24 * 30);
            //change to 3.4 bulan example
            $item->periode_kegiatan = number_format($item->periode_kegiatan, 1) . ' bulan';

            //if is array prodi_id
            if (is_array(json_decode($item->prodi_id))) {
                $item->prodi = ProdiModel::whereIn('prodi_id', json_decode($item->prodi_id))
                    ->pluck('prodi_name')
                    ->implode(', ');
            } else {
                $item->prodi = '-';
            }

            return $item;
        });
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function create()
    {
        $this->authAction('create', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $page = [
            'url' => $this->menuUrl,
            'title' => 'Tambah ' . $this->menuTitle
        ];

        $tipes = TipeKegiatanModel::selectRaw("tipe_kegiatan_id, nama_kegiatan")
            ->get();

        $jenises = JenisMagangModel::selectRaw("jenis_magang_id, nama_magang")
            ->get();

        $periodes = PeriodeModel::selectRaw("periode_id, semester, tahun_ajar")
            ->get();

        $prodis = ProdiModel::selectRaw("prodi_id, prodi_name, prodi_code")
            ->get();

        return view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('tipes', $tipes)
            ->with('jenises', $jenises)
            ->with('periodes', $periodes)
            ->with('prodis', $prodis)
            ->with('koordinator', false);
    }


    public function store(Request $request)
    {
        $this->authAction('create', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'tipe_kegiatan_id' => 'required',
                'posisi_lowongan' => 'required|string',
                'deskripsi' => 'required|string',
                'kuota' => 'required|numeric',
                'mulai_kegiatan' => 'required|date',
                'akhir_kegiatan' => 'required|date',
                'batas_pendaftaran' => 'required|date',
                'contact_person' => 'required|string',
                'kualifikasi' => 'required|string',
                'fasilitas' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'stat'     => false,
                    'mc'       => false,
                    'msg'      => 'Terjadi kesalahan.',
                    'msgField' => $validator->errors()
                ]);
            }

            $auth = auth()->user();
            $id_perusahaan = PerusahaanModel::where('user_id', $auth->user_id)->first()->perusahaan_id;

            // $request['periode_id'] = json_encode($request->periode_arr);
            $request['prodi_id'] = json_encode($request->prodi_arr);
            // unset($request['periode_arr']);
            unset($request['prodi_arr']);

            $file = $request->file('file');
            if ($file) {
                $fileName = 'flyer_' . time() . '.' . $file->getClientOriginalExtension();
                //move to public/assets/
                $file->move(public_path('assets/flyer'), $fileName);
                $request['flyer'] = $fileName;
            } else {
                $request['flyer'] = null;
            }

            // unset($request['file']);

            //remove file from request


            $request['perusahaan_id'] = $id_perusahaan;
            $role = auth()->user()->group_id;
            $request['status'] = $role == 1 ? 1 : 0;
            $kode_kegiatan = 'K' . rand(100000, 999999);
            $request['kode_kegiatan'] = $kode_kegiatan;
            $res = KegiatanPerusahaanModel::insertData($request, ['file']);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? $this->getMessage('insert.success') : $this->getMessage('insert.failed')
            ]);
        }

        return redirect('/');
    }

    public function edit($id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $page = [
            'url' => $this->menuUrl . '/' . $id,
            'title' => 'Edit ' . $this->menuTitle
        ];

        $data = KegiatanPerusahaanModel::find($id);

        $tipes = TipeKegiatanModel::selectRaw("tipe_kegiatan_id, nama_kegiatan")
            ->get();

        $jenises = JenisMagangModel::selectRaw("jenis_magang_id, nama_magang")
            ->get();

        $periodes = PeriodeModel::selectRaw("periode_id, semester, tahun_ajar")
            ->where('is_active', 1)
            ->get();

        $prodis = ProdiModel::selectRaw("prodi_id, prodi_name, prodi_code")
            ->get();

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data)
            ->with('tipes', $tipes)
            ->with('jenises', $jenises)
            ->with('periodes', $periodes)
            ->with('prodis', $prodis)
            ->with('koordinator', false);
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'tipe_kegiatan_id' => 'required',
                'posisi_lowongan' => 'required|string',
                'deskripsi' => 'required|string',
                'kuota' => 'required|numeric',
                'mulai_kegiatan' => 'required|date',
                'akhir_kegiatan' => 'required|date',
                // 'periode_arr' => 'required',
                // 'prodi_arr' => 'required',
                'batas_pendaftaran' => 'required|date',
                'contact_person' => 'required|string',
                'kualifikasi' => 'required|string',
                'fasilitas' => 'required|string',
            ];

            // $request['periode_id'] = json_encode($request->periode_arr);
            $request['prodi_id'] = json_encode($request->prodi_arr);
            // unset($request['periode_arr']);
            unset($request['prodi_arr']);

            // if $request->file is not null then upload and delete the old one
            $file = $request->file('file');
            $kegiatan = KegiatanPerusahaanModel::find($id);
            if ($file) {
                $fileName = 'flyer_' . time() . '.' . $file->getClientOriginalExtension();
                //move to public/assets/
                $file->move(public_path('assets/flyer'), $fileName);
                $request['flyer'] = $fileName;
                //delete old file
                if ($kegiatan->flyer) {
                    unlink(public_path('assets/flyer/' . $kegiatan->flyer));
                }
            } else {
                $request['flyer'] = $kegiatan->flyer;
            }


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'stat'     => false,
                    'mc'       => false,
                    'msg'      => 'Terjadi kesalahan.',
                    'msgField' => $validator->errors()
                ]);
            }

            $res = KegiatanPerusahaanModel::updateData($id, $request, ['file']);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? $this->getMessage('update.success') : $this->getMessage('update.failed')
            ]);
        }

        return redirect('/');
    }

    public function show($id)
    {
        $this->authAction('read', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $kegiatan = KegiatanPerusahaanModel::where('kegiatan_perusahaan_id', $id)
            ->with('tipe_kegiatan')
            ->first();

        $page = [
            'title' => 'Detail ' . $this->menuTitle
        ];

        $datas = [];

        $kegiatan->durasi = (strtotime($kegiatan->akhir_kegiatan) - strtotime($kegiatan->mulai_kegiatan)) / (60 * 60 * 24 * 30);
        //change to 3.4 bulan example
        $kegiatan->durasi = number_format($kegiatan->durasi, 1) . ' bulan';

        //if is array prodi_id
        if (is_array(json_decode($kegiatan->prodi_id))) {
            $kegiatan->prodi = ProdiModel::whereIn('prodi_id', json_decode($kegiatan->prodi_id))
                ->pluck('prodi_name')
                ->implode(', ');
        } else {
            $kegiatan->prodi = '-';
        }

        //periode semester - tahun_ajar

        //if is array periode_id
        if (is_array(json_decode($kegiatan->periode_id))) {
            $kegiatan->periode = PeriodeModel::whereIn('periode_id', json_decode($kegiatan->periode_id))
                ->get();

            $kegiatan->periode = $kegiatan->periode->map(function ($item) {
                return $item->semester . ' - ' . $item->tahun_ajar;
            })->implode(', ');
        } else {
            $kegiatan->periode = '-';
        }

        $datas = [
            [
                "title" => "Kode Kegiatan",
                "value" => $kegiatan->kode_kegiatan,
                "bold" => true
            ],
            [
                "title" => "Tipe Kegiatan",
                "value" => $kegiatan->tipe_kegiatan->nama_kegiatan,
                "bold" => false
            ],
            [
                "title" => "Jenis Magang",
                "value" => $kegiatan->jenis_magang->nama_magang ?? '-',
                "bold" => false
            ],
            [
                "title" => "Prodi",
                "value" => $kegiatan->prodi,
                "bold" => false
            ],
            [
                "title" => "Periode",
                "value" => $kegiatan->periode,
                "bold" => false
            ],
            [
                "title" => "Posisi Lowongan",
                "value" => $kegiatan->posisi_lowongan,
                "bold" => true
            ],
            [
                "title" => "Deskripsi",
                "value" => $kegiatan->deskripsi,
                "bold" => false
            ],
            [
                "title" => "Kualifikasi",
                "value" => $kegiatan->kualifikasi ?? '-',
                "bold" => false
            ],
            [
                "title" => "Fasilitas/Benefit",
                "value" => $kegiatan->fasilitas ?? '-',
                "bold" => false
            ],
            [
                "title" => "Kuota",
                "value" => $kegiatan->kuota,
                "bold" => false
            ],
            [
                "title" => "Batas Pendaftaran",
                "value" => $kegiatan->batas_pendaftaran,
                "bold" => false
            ],
            [
                "title" => "Mulai Kegiatan",
                "value" => $kegiatan->mulai_kegiatan,
                "bold" => false
            ],
            [
                "title" => "Akhir Kegiatan",
                "value" => $kegiatan->akhir_kegiatan,
                "bold" => false
            ],
            [
                "title" => "Durasi",
                "value" => $kegiatan->durasi,
                "bold" => true
            ],
            [
                "title" => "Flyer",
                "value" => $kegiatan->flyer ? '<a href="' . url('assets/flyer/' . $kegiatan->flyer) . '" target="_blank">Lihat</a>' : '-',
                "bold" => true
            ],
            [
                "title" => "Status",
                "value" => $kegiatan->status == 0 ? 'Menunggu' : ($kegiatan->status == 1 ? 'Diterima' : 'Ditolak'),
                "bold" => false,
                "color" => $kegiatan->status == 0 ? 'info' : ($kegiatan->status == 1 ? 'success' : 'danger')
            ],
            [
                "title" => "Keterangan Ditolak",
                "value" => $kegiatan->keterangan ?? '-',
                "bold" => false
            ]
        ];


        // if status != 2 then remove last index
        if ($kegiatan->status != 2) {
            array_pop($datas);
        }

        //change to stdClass loop
        $datas = array_map(function ($item) {
            $obj = new stdClass;
            $obj->title = $item['title'];
            $obj->value = $item['value'];
            $obj->bold = $item['bold'];
            $obj->color = $item['color'] ?? null;
            return $obj;
        }, $datas);

        // dd($datas);


        return (!$datas) ? $this->showModalError() :
            view($this->viewPath . 'detail')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('datas', $datas);
    }


    public function confirm($id)
    {
        $this->authAction('delete', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = KegiatanPerusahaanModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl  . '/' . $id, [
                'Nama Kegiatan' => $data->posisi_lowongan,
            ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authAction('delete', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $res = KegiatanPerusahaanModel::deleteData($id);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => KegiatanPerusahaanModel::getDeleteMessage()
            ]);
        }

        return redirect('/');
    }
}
