<?php

namespace App\Http\Controllers;

use App\Models\View\BeritaView;
use App\Models\View\DosenProposalView;
use App\Models\View\DosenQuotaProdiView;
use App\Models\View\DosenQuotaView;
use App\Models\View\RekapDosenProdiView;
use App\Models\View\RekapMahasiswaProdiView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;


class DashboardController extends Controller
{

    public function __construct(){
        $this->menuCode  = 'DASHBOARD';
        $this->menuUrl   = url('/');     // set URL untuk menu ini
        $this->menuTitle = 'Halaman Utama';                       // set nama menu
        $this->viewPath  = 'dashboard.';
    }


    public function index(){
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle . ' - ' . Auth::user()->getRoleName(),        // judul menu
            'list' => ['Dashboard']             // breadcrumb
        ];

        // untuk set aktif menu pada sidebar
        $activeMenu = [
            'l1' => 'dashboard',            // menu aktif untuk level 1, berdasarkan class yang ada di sidebar
            'l2' => null,                   // menu aktif untuk level 2, berdasarkan class yang ada di sidebar
            'l3' => null                    // menu aktif untuk level 3, berdasarkan class yang ada di sidebar
        ];


        $page = [
            'url' => $this->menuUrl,
            'title' => $this->menuTitle
        ];

        /*return match (Auth::user()->getRole()) {
            'SPR', 'ADM' => $this->index_admin($breadcrumb, $activeMenu, $page),
            'DSN' => $this->index_dosen($breadcrumb, $activeMenu, $page),
            'MHS' => $this->index_mahasiswa($breadcrumb, $activeMenu, $page),
            default => $this->index_default($breadcrumb, $activeMenu, $page),
        };*/

        switch (Auth::user()->getRole()){
            case 'SPR': return $this->index_admin($breadcrumb, $activeMenu, $page); break;
            case 'ADM' : return $this->index_admin($breadcrumb, $activeMenu, $page); break;
            case 'DSN' : return $this->index_dosen($breadcrumb, $activeMenu, $page); break;
            case 'MHS' : return $this->index_mahasiswa($breadcrumb, $activeMenu, $page); break;
            default : return $this->index_default($breadcrumb, $activeMenu, $page); break;
        }
    }

    private function index_default($breadcrumb, $activeMenu, $page){
        return view($this->viewPath .'.default.index')
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('page', (object) $page);
    }

    private function index_admin($breadcrumb, $activeMenu, $page){
        return $this->index_default($breadcrumb, $activeMenu, $page);
    }

    private function index_dosen($breadcrumb, $activeMenu, $page){
        return $this->index_default($breadcrumb, $activeMenu, $page);
    }

    private function index_mahasiswa($breadcrumb, $activeMenu, $page){
        return $this->index_default($breadcrumb, $activeMenu, $page);
    }

    public function quota_dosen(Request $request){
        $this->authAction('read', 'json');
        if($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = DosenQuotaView::selectRaw("dosen_nip, dosen_nidn, dosen_name, quota, jumlah_proposal, jumlah_bimbingan")
            ->where('periode_id', getPeriodeID());

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function berita(Request $request){
        $this->authAction('read', 'json');
        if($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = BeritaView::selectRaw("berita_uid, prodi_code, kategori_name, berita_judul, date_format(created_at, '%d %b %Y %H:%i') as tanggal, created_by")
            ->where('jurusan_id', getJurusanID())
            ->where(function($query){
                $query->where('prodi_id', getProdiID())
                    ->orWhereNull('prodi_id');
            })
            ->where('berita_status', '1')
            ->orderBy('created_at', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function berita_detail(Request $request, $uid){
        $this->authAction('read', 'modal');
        if($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = BeritaView::where('berita_uid', $uid)->first();

        return (!$data)? $this->showModalError() :
            view($this->viewPath . 'detail_berita')
                ->with('data', $data);
    }
}
