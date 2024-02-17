<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\MahasiswaModel;
use App\Models\Master\PeriodeModel;
use App\Models\Master\ProdiModel;
use App\Models\MitraKuotaModel;
use App\Models\MitraModel;
use App\Models\Transaction\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class DaftarMagangController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'TRANSACTION.DAFTAR.MAGANG';
        $this->menuUrl   = url('transaksi/daftar-magang');     // set URL untuk menu ini
        $this->menuTitle = 'Daftar Magang';                       // set nama menu
        $this->viewPath  = 'transaction.daftar-magang.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Transaksi', 'Daftar Magang']
        ];

        $activeMenu = [
            'l1' => 'transaction',
            'l2' => 'transaksi-daftar-magang',
            'l3' => null
        ];

        $page = [
            'url' => $this->menuUrl,
            'title' => $this->menuTitle
        ];

        return view($this->viewPath . 'index')
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('page', (object) $page)
            ->with('allowAccess', $this->authAccessKey());
    }

    public function list(Request $request)
    {
        $this->authAction('read', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = MitraModel::with('kegiatan')
            ->with('periode')
            ->where('status', 1)
            ->get();

        $data = $data->map(function ($item) {
            //TODO: get jumlah pendaftar
            $item['mitra_jumlah_pendaftar'] = 0;
            return $item;
        });

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $this->authAction('read', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = MitraModel::find($id);
        $page = [
            'title' => 'Detail ' . $this->menuTitle
        ];

        $mitra = MitraModel::where('mitra_id', $id)
            ->with('kegiatan')
            ->with('periode')
            ->first();

        $prodi_id = MahasiswaModel::where('user_id', Auth::user()->user_id)->first()->prodi_id;

        $kuota = MitraKuotaModel::where('mitra_id', $id)
            ->where('prodi_id', $prodi_id)
            ->first();

        $kuota = ($kuota) ? $kuota->kuota : 0;

        $datas = [
            [
                "title" => "Nama Kegiatan",
                "value" => $mitra->kegiatan->kegiatan_nama,
                "bold" => false
            ],
            [
                "title" => "Nama Mitra",
                "value" => $mitra->mitra_nama,
                "bold" => true
            ],
            [
                "title" => "Periode",
                "value" => $mitra->periode->periode_nama,
                "bold" => false
            ],
            [
                "title" => "Deskripsi",
                "value" => $mitra->mitra_deskripsi,
                "bold" => false
            ],
            [
                "title" => "Durasi",
                "value" => $mitra->mitra_durasi . ' bulan',
                "bold" => true
            ], [
                "title" => "Kuota",
                "value" => $kuota,
                "bold" => false
            ],
        ];

        //change to stdClass loop
        $datas = array_map(function ($item) {
            $obj = new stdClass;
            $obj->title = $item['title'];
            $obj->value = $item['value'];
            $obj->bold = $item['bold'];
            $obj->color = $item['color'] ?? null;
            return $obj;
        }, $datas);

        $mahasiswa_id = MahasiswaModel::where('user_id', Auth::user()->user_id)->first()->mahasiswa_id;
        $mahasiswas = MahasiswaModel::where('prodi_id', $prodi_id)
            ->get();

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'detail')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data)
            ->with('datas', $datas)
            ->with('url', $this->menuUrl . '/' . $id . '/daftar')
            ->with('mahasiswa_id', $mahasiswa_id)
            ->with('mahasiswas', $mahasiswas)
            ->with('action', 'POST');
    }

    public function daftar(Request $request, $id_mitra)
    {
        $id_periode = PeriodeModel::where('is_current', 1)->first()->periode_id;
        $tipe_pendaftar = $request->tipe_pendaftar;
        $mahasiswa = $request->mahasiswa;

        if ($tipe_pendaftar == 2) {
            $id_mahasiswa = $mahasiswa[0];
            $prodi_id = MahasiswaModel::where('mahasiswa_id', $id_mahasiswa)->first()->prodi_id;

            //cek in Magang id_mahasiswa and id_periode
            //if exist, return error
            $cek = Magang::where('mahasiswa_id', $id_mahasiswa)
                ->where('periode_id', $id_periode)
                ->first();

            if ($cek) {
                return response()->json([
                    'stat' => false,
                    'mc' => false, // close modal
                    'msg' => 'Anda sudah mendaftar magang'
                ]);
            }

            $kode = 'P-' . rand(1000, 9999);

            $request['mahasiswa_id'] = $id_mahasiswa;
            $request['mitra_id'] = $id_mitra;
            $request['periode_id'] = $id_periode;
            $request['prodi_id'] = $prodi_id;
            $request['magang_kode'] = $kode;
            $request['magang_tipe'] = $tipe_pendaftar;
            $request['status'] = 0;

            unset($request['mahasiswa']);
            unset($request['tipe_pendaftar']);
            // dd($request->all());
            $res = Magang::insertData($request);
        } else {
            $cek = Magang::whereIn('mahasiswa_id', $mahasiswa)
                ->where('periode_id', $id_periode)
                ->first();

            if ($cek) {
                return response()->json([
                    'stat' => false,
                    'mc' => false, // close modal
                    'msg' => 'Salah satu mahasiswa sudah mendaftar magang'
                ]);
            }

            $kode = 'P-' . rand(1000, 9999);

            foreach ($mahasiswa as $index => $m) {
                $id_mahasiswa = $m;
                $prodi_id = MahasiswaModel::where('mahasiswa_id', $id_mahasiswa)->first()->prodi_id;

                //cek in Magang id_mahasiswa and id_periode
                //if exist, return error


                $request['mahasiswa_id'] = $id_mahasiswa;
                $request['mitra_id'] = $id_mitra;
                $request['periode_id'] = $id_periode;
                $request['prodi_id'] = $prodi_id;
                $request['magang_kode'] = $kode;
                $request['magang_tipe'] = $index = 0 ? 0 : 1;
                $request['status'] = 0;

                unset($request['mahasiswa']);
                unset($request['tipe_pendaftar']);
                // dd($request->all());
                $res = Magang::insertData($request);
            }
        }

        return response()->json([
            'stat' => $res,
            'mc' => $res, // close modal
            'msg' => $res ? 'Berhasil mendaftar' : 'Gagal mendaftar'
        ]);

        // dd($request->all(), $id_mitra, $id_periode, $tipe_pendaftar, $id_mahasiswa);
    }
}
