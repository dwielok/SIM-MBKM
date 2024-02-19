<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction\Magang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PendaftaranController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'TRANSACTION.PENDAFTARAN';
        $this->menuUrl   = url('transaksi/pendaftaran');     // set URL untuk menu ini
        $this->menuTitle = 'Pendaftaran MBKM';                       // set nama menu
        $this->viewPath  = 'transaction.pendaftaran.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Transaksi', 'Pendaftaran MBKM']
        ];

        $activeMenu = [
            'l1' => 'transaction',
            'l2' => 'transaksi-pendaftaran',
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

    public function confirm_approve($id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = Magang::with('mahasiswa')
            ->with('mitra')
            ->with('periode')
            ->with('prodi')
            ->with('mitra.kegiatan')
            ->where('magang_id', $id)
            ->first();

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id . '/approve', [
                'NIM' => $data->mahasiswa->nim,
                'Nama' => $data->mahasiswa->nama_mahasiswa,
                'Mitra' => $data->mitra->mitra_nama,
                'Kegiatan' => $data->mitra->kegiatan->kegiatan_nama,
            ], 'Konfirmasi Terima', 'Apakah anda yakin ingin menerima mahasiswa berikut:', 'Ya, Approve', 'PUT');
    }

    public function confirm_reject($id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = Magang::with('mahasiswa')
            ->with('mitra')
            ->with('periode')
            ->with('prodi')
            ->with('mitra.kegiatan')
            ->where('magang_id', $id)
            ->first();

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id . '/reject', [
                'NIM' => $data->mahasiswa->nim,
                'Nama' => $data->mahasiswa->nama_mahasiswa,
                'Mitra' => $data->mitra->mitra_nama,
                'Kegiatan' => $data->mitra->kegiatan->kegiatan_nama,
            ], 'Konfirmasi Tolak', 'Apakah anda yakin ingin menolak mahasiswa berikut:', 'Ya, Reject', 'PUT');
    }
}
