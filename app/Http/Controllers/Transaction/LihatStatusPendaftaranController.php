<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\DokumenMagangModel;
use App\Models\MitraModel;
use App\Models\Transaction\Magang;
use Illuminate\Http\Request;
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
            $id = $id;
            $dokumen = DokumenMagangModel::where('magang_id', $id);
            $anggota = NULL;
        } else {
            $magang = Magang::where('magang_kode', $data->magang_kode)->get();
            $id = $magang->pluck('magang_id');
            $dokumen = DokumenMagangModel::whereIn('magang_id', $id);
            if ($data->magang_tipe == 0) {
                $anggota = Magang::whereIn('magang_id', $id)->with('mahasiswa')->where('magang_tipe', '=', 1)->get();
            } else {
                $anggota = NULL;
            }
        }

        // dd($dokumen->get());


        // dd($anggota);

        $datas = [
            [
                "title" => "Proposal",
                "value" => $dokumen->where('dokumen_magang_nama', 'PROPOSAL')->first() ? $dokumen->where('dokumen_magang_nama', 'PROPOSAL')->first()->dokumen_magang_file : "Belum Ada File",
                "bold" => false,
                "link" => $dokumen->where('dokumen_magang_nama', 'PROPOSAL')->first() ? TRUE : FALSE,
            ],
            [
                "title" => "Bukti Lolos",
                "value" => $dokumen->where('dokumen_magang_nama', 'BUKTI LOLOS')->first() ? $dokumen->where('dokumen_magang_nama', 'BUKTI LOLOS')->first()->dokumen_magang_file : "Belum Ada File",
                "bold" => false,
                "link" => $dokumen->where('dokumen_magang_nama', 'BUKTI LOLOS')->first() ? TRUE : FALSE,
            ],
        ];

        if ($mitra->kegiatan->is_submit_proposal == 0) {
            //remove kuota
            unset($datas[0]);
        }

        //change to stdClass loop
        $datas = array_map(function ($item) {
            $obj = new stdClass;
            $obj->title = $item['title'];
            $obj->value = $item['value'];
            $obj->bold = $item['bold'];
            $obj->link = $item['link'];
            $obj->color = $item['color'] ?? null;
            return $obj;
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
}
