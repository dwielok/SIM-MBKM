<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction\KuotaDosenModel;
use App\Models\Setting\UserModel;
use App\Models\Master\ProdiModel;
use App\Models\Master\DosenModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KuotaDosenSIBController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'KUOTA.DOSEN';
        $this->menuUrl   = url('kuota/dosen');
        $this->menuTitle = 'Kuota';
        $this->viewPath  = 'transaction.';
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Kuota', 'DOSEN']
        ];

        $activeMenu = [
            'l1' => 'kuota',
            'l2' => 'kuota-dosen',
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
            ->with('allowAccess', $this->authAccessKey());
    }

    public function list(Request $request)
    {
        $this->authAction('read', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = KuotaDosenModel::with('dosen')
        ->select('dosen_id', 'count_advisor_TI', 'count_advisor_SIB', 'count_advisor_PPLS')
        ->get();

        // Menghitung jumlah total dari kolom count_advisor_TI
        $total_TI = $data->sum('count_advisor_TI');

        // Menghitung jumlah total dari kolom count_advisor_SIB
        $total_SIB = $data->sum('count_advisor_SIB');

        // Menghitung jumlah total dari kolom count_advisor_PPLS
        $total_PPLS = $data->sum('count_advisor_PPLS');

        // Menambahkan kolom Jumlah ke setiap data
        foreach ($data as $row) {
            $row->jumlah_total = $row->count_advisor_TI + $row->count_advisor_SIB + $row->count_advisor_PPLS;
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('dosen_name', function ($row) {
                return $row->dosen ? $row->dosen->dosen_name : 'Belum ada data dosen';
            })
            ->addColumn('jumlah_total', function ($row) {
                return $row->jumlah_total;
            })
            ->make(true);
    }

}
