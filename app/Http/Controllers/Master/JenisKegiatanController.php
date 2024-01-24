<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisKegiatanModel;
use App\Models\Master\JenisProgramModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class JenisKegiatanController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'MASTER.JENIS.KEGIATAN';
        $this->menuUrl   = url('master/jenis_kegiatan');     // set URL untuk menu ini
        $this->menuTitle = 'Jenis Kegiatan';                       // set nama menu
        $this->viewPath  = 'master.jenis_kegiatan.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Data Master', 'Jenis Magang']
        ];

        $activeMenu = [
            'l1' => 'master',
            'l2' => 'master-jenis-magang',
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

        $data  = JenisKegiatanModel::with('jenis_program')->get();

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

        $programs = JenisProgramModel::all();

        return view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('programs', $programs);
    }


    public function store(Request $request)
    {
        $this->authAction('create', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'nama_kegiatan' => 'required|string|max:100',
                'jenis_program_id' => 'required|integer',
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

            $res = JenisKegiatanModel::insertData($request);

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

        $data = JenisKegiatanModel::find($id);
        $programs = JenisProgramModel::all();

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data)
            ->with('programs', $programs);
    }


    public function update(Request $request, $id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'nama_kegiatan' => 'required|string|max:100',
                'jenis_program_id' => 'required|integer',
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

            $res = JenisKegiatanModel::updateData($id, $request);

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

        $data = JenisKegiatanModel::find($id);
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

        $data = JenisKegiatanModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id, [
                'Nama' => $data->nama_magang,
            ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->authAction('delete', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $res = JenisKegiatanModel::deleteData($id);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => JenisKegiatanModel::getDeleteMessage()
            ]);
        }

        return redirect('/');
    }
}
