<?php

namespace App\Http\Controllers;

use App\Models\Master\KegiatanPerusahaanModel;
use App\Models\Master\PeriodeModel;
use App\Models\Master\PerusahaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PerusahaanController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'PERUSAHAAN';
        $this->menuUrl   = url('perusahaan');     // set URL untuk menu ini
        $this->menuTitle = 'Perusahaan';                       // set nama menu
        $this->viewPath  = 'mahasiswa.perusahaan.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Data Master', 'Perusahaan']
        ];

        $activeMenu = [
            'l1' => 'perusahaan',
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

        $periode_active = PeriodeModel::where('is_current', 1)->first();

        $data  = KegiatanPerusahaanModel::selectRaw("periode_id, kegiatan_perusahaan_id, kode_kegiatan, posisi_lowongan, deskripsi, kuota, mulai_kegiatan, akhir_kegiatan, status, keterangan")
            ->where('status', 1)
            ->whereRaw("JSON_CONTAINS(periode_id, ?)", [$periode_active->periode_id]);


            $results = KegiatanPerusahaanModel::all()
            ->filter(function ($item) use ($periode_active) {
                $periodeIds = json_decode($item->periode_id, true);
                return in_array($periode_active->periode_id, $periodeIds);
            });

            dd($results);
        //combine mulai_kegiatan and akhir_kegiatan, and calculate to (x bulan) to periode_kegiatan
        $data->addSelect(DB::raw("CONCAT(mulai_kegiatan, ' - ', akhir_kegiatan, ' (', TIMESTAMPDIFF(MONTH, mulai_kegiatan, akhir_kegiatan), ' bulan)') as periode_kegiatan"));

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
