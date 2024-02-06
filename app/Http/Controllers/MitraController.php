<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KabupatenModel;
use App\Models\Master\KegiatanModel;
use App\Models\Master\PeriodeModel;
use App\Models\MitraModel;
use App\Models\ProvinsiModel;
use App\Models\Setting\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MitraController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'MITRA';
        $this->menuUrl   = url('mitra');     // set URL untuk menu ini
        $this->menuTitle = 'Mitra';                       // set nama menu
        $this->viewPath  = 'mitra.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Mitra']
        ];

        $activeMenu = [
            'l1' => 'mitra',
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
            ->with('allowAccess', $this->authAccessKey());
    }

    public function list(Request $request)
    {
        $this->authAction('read', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = MitraModel::with('kegiatan')
            ->with('periode')
            ->get();

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


        $provinsis = ProvinsiModel::all();
        $periodes = PeriodeModel::where('is_active', 1)->get();
        $kegiatans = KegiatanModel::all();
        $kabupatens = [];

        return view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('periodes', $periodes)
            ->with('kegiatans', $kegiatans)
            ->with('provinsis', $provinsis)
            ->with('kabupatens', $kabupatens);
    }


    public function store(Request $request)
    {
        $this->authAction('create', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'kegiatan_id' => 'required',
                'periode_id' => 'required',
                'mitra_nama' => 'required|string',
                'mitra_alamat' => 'required',
                'mitra_website' => 'required',
                'mitra_deskripsi' => 'required',
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

            // $random_username = 'per_' . rand(100000, 999999);
            // $user = [
            //     'username' => $random_username,
            //     'name' => $request->nama_perusahaan,
            //     'password' => Hash::make($random_username),
            //     'group_id' => 5,
            //     'is_active' => 1,
            //     'email' => $request->email,
            // ];
            // $insert = UserModel::create($user);

            // $request['user_id'] = $insert->user_id;

            $res = MitraModel::insertData($request);



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

        $data = MitraModel::find($id);

        $provinsis = ProvinsiModel::all();
        $periodes = PeriodeModel::where('is_active', 1)->get();
        $kegiatans = KegiatanModel::all();
        $kabupatens = KabupatenModel::where('d_provinsi_id', $data->provinsi_id)->get();

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('periodes', $periodes)
            ->with('kegiatans', $kegiatans)
            ->with('provinsis', $provinsis)
            ->with('kabupatens', $kabupatens)
            ->with('data', $data);
    }


    public function update(Request $request, $id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'kegiatan_id' => 'required',
                'periode_id' => 'required',
                'mitra_nama' => 'required|string',
                'mitra_alamat' => 'required',
                'mitra_website' => 'required',
                'mitra_deskripsi' => 'required',
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

            $res = MitraModel::updateData($id, $request);

            // $id_perusahaan = MitraModel::where('perusahaan_id', $id)->first();

            // $res_user = UserModel::where('user_id', $id_perusahaan->user_id)->update([
            //     'name' => $request->nama_perusahaan,
            //     'email' => $request->email,
            // ]);

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

        $data = MitraModel::find($id);
        $page = [
            'title' => 'Detail ' . $this->menuTitle
        ];

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'detail')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data);
    }


    public function confirm($id)
    {
        $this->authAction('delete', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = MitraModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id, [
                'Nama' => $data->mitra_nama,
            ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authAction('delete', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $res = MitraModel::deleteData($id);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => MitraModel::getDeleteMessage()
            ]);
        }

        return redirect('/');
    }

    public function confirm_approve($id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = MitraModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id . '/approve', [
                'Nama' => "$data->nama_perusahaan",
            ], 'Konfirmasi Approve Perusahaan', 'Apakah anda yakin ingin approve perusahaan berikut:', 'Ya, Approve', 'PUT');
    }

    public function confirm_reject($id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = MitraModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalReject($this->menuUrl . '/' . $id . '/reject', [
                'Nama' => "$data->nama_perusahaan",
            ], 'Konfirmasi Reject Perusahaan', 'Apakah anda yakin ingin reject perusahaan berikut:', 'Ya, Reject', 'PUT');
    }

    public function approve(Request $request, $id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $request['status'] = 1; // [0: pending, 1: approved, 2: rejected]
            $res = MitraModel::updateData($id, $request);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? 'Perusahaan berhasil diapprove.' : 'Perusahaan gagal diapprove.'
            ]);
        }

        return redirect('/');
    }

    public function reject(Request $request, $id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $request['status'] = 2; // [0: pending, 1: approved, 2: rejected]
            $request['keterangan'] = $request->reason;
            unset($request['reason']);
            $res = MitraModel::updateData($id, $request);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? 'Perusahaan berhasil direject.' : 'Perusahaan gagal direject.'
            ]);
        }

        return redirect('/');
    }
}
