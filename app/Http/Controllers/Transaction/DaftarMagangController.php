<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Master\MahasiswaModel;
use App\Models\Master\ProdiModel;
use App\Models\MitraKuotaModel;
use App\Models\MitraModel;
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
                "value" => $kuota->kuota,
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
            ->with('action', 'PUT');
    }
}
