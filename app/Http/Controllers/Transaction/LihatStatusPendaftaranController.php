<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\DokumenMagangModel;
use App\Models\MitraModel;
use App\Models\Transaction\Magang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class LihatStatusPendaftaranController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'TRANSACTION.LIHAT.STATUS.PENDAFTARAN';
        $this->menuUrl   = url('transaksi/lihat-status-pendaftaran');     // set URL untuk menu ini
        $this->menuTitle = 'Lihat Status Pendafatran';                       // set nama menu
        $this->viewPath  = 'transaction.lihat-status-pendaftaran.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Transaksi', 'Lihat Status Pendafatran']
        ];

        $activeMenu = [
            'l1' => 'transaction',
            'l2' => 'transaksi-lihat-status-pendaftaran',
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


        $data  = Magang::with('mahasiswa')
            ->with('mitra')
            ->with('periode')
            ->with('prodi')
            ->with('mitra.kegiatan');

        if (auth()->user()->group_id == 1) {
            $data = $data->get();
        } else if (auth()->user()->group_id == 4) {
            $data = $data->where('mahasiswa_id', auth()->user()->getUserMahasiswa->mahasiswa_id)->get();
        } else {
            $prodi_id = auth()->user()->getProdiId();
            $data = $data->where('prodi_id', $prodi_id)->get();
        }

        $data = $data->map(function ($item) {
            $item['encrypt_magang_id'] = Crypt::encrypt($item->magang_id);
            $item['proposal'] = DokumenMagangModel::where('magang_id', $item->magang_id)->where('dokumen_magang_nama', 'PROPOSAL')->first();
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

        $page = [
            'title' => 'Detail ' . $this->menuTitle
        ];

        $data = Magang::find($id);

        $mitra = MitraModel::where('mitra_id', $data->mitra_id)
            ->with('kegiatan')
            ->with('periode')
            ->first();

        if ($data->magang_tipe == 2) {
            $dokumen = DokumenMagangModel::where('magang_id', $id);
            $anggota = NULL;
        } else {
            $magang = Magang::where('magang_kode', $data->magang_kode)->get();
            $id = $magang->pluck('magang_id');
            $dokumen = DokumenMagangModel::whereIn('magang_id', $id);
            $anggota = ($data->magang_tipe == 0) ? Magang::whereIn('magang_id', $id)->with('mahasiswa')->where('magang_tipe', '=', 1)->get() : NULL;
        }

        $datas = [
            [
                "title" => "Proposal",
                "nama" => "PROPOSAL"
            ],
            [
                "title" => "Surat Balasan",
                "nama" => "SURAT_BALASAN"
            ]
        ];

        foreach ($datas as &$data) {
            $dokumenItem = $dokumen->where('dokumen_magang_nama', $data['nama'])->first();
            $data['value'] = $dokumenItem ? $dokumenItem->dokumen_magang_file : "Belum Ada File";
            $data['bold'] = false;
            $data['link'] = $dokumenItem ? true : false;
            unset($data['nama']);
        }

        if ($mitra->kegiatan->is_submit_proposal == 0) {
            unset($datas[0]);
        }

        // Convert to stdClass objects
        $datas = array_map(function ($item) {
            return (object) $item;
        }, $datas);


        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'detail')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data)
            ->with('datas', $datas)
            ->with('anggota', $anggota)
            ->with('mitra', $data);
    }

    public function lengkapi($id)
    {
        $this->authAction('read', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $id = Crypt::decrypt($id);

        $data = Magang::find($id);

        $kode_magang = $data->magang_kode;

        //find Magang with same kode_magang then show mahaasiswa

        $anggotas = Magang::where('magang_kode', $kode_magang)
            ->with('mahasiswa')
            ->get();

        $dateString = $data->mitra_batas_pendaftaran;
        $currentDate = date('Y-m-d');
        $disabled = strtotime($dateString) < strtotime($currentDate);

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Transaksi', 'Lihat Status Pendafatran']
        ];

        $activeMenu = [
            'l1' => 'transaction',
            'l2' => 'transaksi-lihat-status-pendaftaran',
            'l3' => null
        ];

        $page = [
            'url' => $this->menuUrl,
            'title' => $this->menuTitle
        ];

        $magang = Magang::where('magang_id', $id)
            ->with('mitra')
            ->with('mitra.kegiatan')
            ->with('periode')
            ->first();

        $datas = [
            [
                "title" => "Nama Kegiatan",
                "value" => $magang->mitra->kegiatan->kegiatan_nama,
                "textarea" => false
            ],
            [
                "title" => "Nama Mitra",
                "value" => $magang->mitra->mitra_nama,
                "textarea" => false
            ],
            [
                "title" => "Periode",
                "value" => $magang->mitra->periode->periode_nama,
                "textarea" => false
            ],
            [
                "title" => "Durasi",
                "value" => $magang->mitra->mitra_durasi . ' bulan',
                "textarea" => false
            ],  [
                "title" => "Skema",
                "value" => $magang->magang_skema,
                "textarea" => false
            ],  [
                "title" => "Tanggal Pendaftaran",
                "value" => Carbon::parse($magang->mitra->mitra_batas_pendaftaran)->format('d M Y'),
                "textarea" => false
            ]
        ];

        //change to stdClass loop
        $datas = array_map(function ($item) {
            $obj = new stdClass;
            $obj->title = $item['title'];
            $obj->value = $item['value'];
            $obj->textarea = $item['textarea'];
            $obj->color = $item['color'] ?? null;
            return $obj;
        }, $datas);

        return view($this->viewPath . 'update')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data)
            ->with('datas', $datas)
            ->with('magang', $data)
            ->with('url', $this->menuUrl . '/' . $id . '/suratbalasan')
            ->with('disabled', $disabled)
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('anggotas', $anggotas)
            ->with('page', (object) $page)
            ->with('action', 'POST');
    }

    public function suratbalasan(Request $request, $id_magang)
    {

        $file = $request->file('surat_balasan');
        if ($file) {
            $fileName = 'suratbalasan' . time() . '.' . $file->getClientOriginalExtension();
            //move to public/assets/
            $file->move(public_path('assets/suratbalasan'), $fileName);
            // $request['dokumen_magang_file'] = $fileName;

            $id_mahasiswa = $request->mahasiswa_id;

            $res = DokumenMagangModel::create([
                'mahasiswa_id' => $id_mahasiswa,
                'magang_id' => $id_magang,
                'dokumen_magang_tipe' => $request->dokumen_magang_tipe,
                'dokumen_magang_nama' => 'SURAT_BALASAN',
                'dokumen_magang_file' => $fileName
            ]);
            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => $res ? 'Berhasil upload surat balasan' : 'Gagal upload surat balasan'
            ]);
        } else {
            return response()->json([
                'stat' => false,
                'mc' => false,
                'msg' => 'Pilih file terlebih dahulu'
            ]);
        }



        // dd($request->all(), $id_mitra, $id_periode, $tipe_pendaftar, $id_mahasiswa);
    }
}
