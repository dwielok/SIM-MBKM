<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisMagangModel;
use App\Models\Master\KegiatanPerusahaanModel;
use App\Models\Master\PeriodeModel;
use App\Models\Master\TipeKegiatanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    public function __construct()
    {
        $this->menuCode  = 'MASTER.PERUSAHAAN';
        $this->menuUrl   = url('master/perusahaan');     // set URL untuk menu ini
        $this->menuTitle = 'Kegiatan';                       // set nama menu
        $this->viewPath  = 'master.kegiatan.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index($id)
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Data Master', 'Perusahaan', 'Kegiatan']
        ];

        $activeMenu = [
            'l1' => 'master',
            'l2' => 'master-perusahaan',
            'l3' => null
        ];

        $page = [
            'url' => $this->menuUrl . '/' . $id . '/kegiatan',
            'title' => 'Daftar ' . $this->menuTitle
        ];

        return view($this->viewPath . 'index')
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('page', (object) $page)
            ->with('allowAccess', $this->authAccessKey())
            ->with('id', $id);
    }

    public function list(Request $request, $id)
    {
        $this->authAction('read', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = KegiatanPerusahaanModel::selectRaw("kegiatan_perusahaan_id, kode_kegiatan, posisi_lowongan, deskripsi, kuota, mulai_kegiatan, akhir_kegiatan, status, keterangan")
            ->where('perusahaan_id', $id);
        //combine mulai_kegiatan and akhir_kegiatan, and calculate to (x bulan) to periode_kegiatan
        $data->addSelect(DB::raw("CONCAT(mulai_kegiatan, ' - ', akhir_kegiatan, ' (', TIMESTAMPDIFF(MONTH, mulai_kegiatan, akhir_kegiatan), ' bulan)') as periode_kegiatan"));

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function create($id)
    {
        $this->authAction('create', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $page = [
            'url' => $this->menuUrl . '/' . $id . '/kegiatan/store',
            'title' => 'Tambah ' . $this->menuTitle
        ];

        $tipes = TipeKegiatanModel::selectRaw("tipe_kegiatan_id, nama_kegiatan")
            ->get();

        $jenises = JenisMagangModel::selectRaw("jenis_magang_id, nama_magang")
            ->get();

        $periodes = PeriodeModel::selectRaw("periode_id, semester, tahun_ajar")
            ->get();


        return view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('tipes', $tipes)
            ->with('jenises', $jenises)
            ->with('periodes', $periodes);
    }


    public function store(Request $request, $id)
    {
        $this->authAction('create', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'tipe_kegiatan_id' => 'required',
                'periode_id' => 'required',
                'posisi_lowongan' => 'required|string',
                'deskripsi' => 'required|string',
                'kuota' => 'required|numeric',
                'mulai_kegiatan' => 'required|date',
                'akhir_kegiatan' => 'required|date',
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

            $request['perusahaan_id'] = $id;
            $role = auth()->user()->group_id;
            $request['status'] = $role == 1 ? 1 : 0;
            $kode_kegiatan = 'K' . rand(100000, 999999);
            $request['kode_kegiatan'] = $kode_kegiatan;
            $res = KegiatanPerusahaanModel::insertData($request);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? $this->getMessage('insert.success') : $this->getMessage('insert.failed')
            ]);
        }

        return redirect('/');
    }

    public function edit($id, $kegiatan_id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $page = [
            'url' => $this->menuUrl . '/' . $id . '/kegiatan/' . $kegiatan_id . '/update',
            'title' => 'Edit ' . $this->menuTitle
        ];

        $data = KegiatanPerusahaanModel::find($kegiatan_id);

        $tipes = TipeKegiatanModel::selectRaw("tipe_kegiatan_id, nama_kegiatan")
            ->get();

        $jenises = JenisMagangModel::selectRaw("jenis_magang_id, nama_magang")
            ->get();

        $periodes = PeriodeModel::selectRaw("periode_id, semester, tahun_ajar")
            ->get();

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'action')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data)
            ->with('tipes', $tipes)
            ->with('jenises', $jenises)
            ->with('periodes', $periodes);
    }


    public function update(Request $request, $id, $kegiatan_id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'tipe_kegiatan_id' => 'required',
                'periode_id' => 'required',
                'posisi_lowongan' => 'required|string',
                'deskripsi' => 'required|string',
                'kuota' => 'required|numeric',
                'mulai_kegiatan' => 'required|date',
                'akhir_kegiatan' => 'required|date',
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

            $res = KegiatanPerusahaanModel::updateData($kegiatan_id, $request);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? $this->getMessage('update.success') : $this->getMessage('update.failed')
            ]);
        }

        return redirect('/');
    }

    public function show($id, $kegiatan_id)
    {
        $this->authAction('read', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = KegiatanPerusahaanModel::find($kegiatan_id);
        $page = [
            'title' => 'Detail ' . $this->menuTitle
        ];

        return (!$data) ? $this->showModalError() :
            view($this->viewPath . 'detail')
            ->with('page', (object) $page)
            ->with('id', $id)
            ->with('data', $data);
    }


    public function confirm($id, $kegiatan_id)
    {
        $this->authAction('delete', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = KegiatanPerusahaanModel::find($kegiatan_id);

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id . '/kegiatan/' . $kegiatan_id . '/destroy', [
                'Nama Kegiatan' => $data->posisi_lowongan,
            ]);
    }

    public function destroy(Request $request, $id, $kegiatan_id)
    {
        $this->authAction('delete', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $res = KegiatanPerusahaanModel::deleteData($kegiatan_id);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => KegiatanPerusahaanModel::getDeleteMessage()
            ]);
        }

        return redirect('/');
    }
    public function confirm_approve($id, $kegiatan_id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = KegiatanPerusahaanModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalConfirm($this->menuUrl . '/' . $id . '/kegiatan/' . $kegiatan_id . '/approve', [
                'Nama' => "$data->posisi_lowongan",
            ], 'Konfirmasi Approve Kegiatan', 'Apakah anda yakin ingin approve kegiatan berikut:', 'Ya, Approve', 'PUT');
    }

    public function confirm_reject($id, $kegiatan_id)
    {
        $this->authAction('update', 'modal');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data = KegiatanPerusahaanModel::find($id);

        return (!$data) ? $this->showModalError() :
            $this->showModalReject($this->menuUrl . '/' . $id . '/kegiatan/' . $kegiatan_id . '/reject', [
                'Nama' => "$data->posisi_lowongan",
            ], 'Konfirmasi Reject Kegiatan', 'Apakah anda yakin ingin reject kegiatan berikut:', 'Ya, Reject', 'PUT');
    }

    public function approve(Request $request, $id, $kegiatan_id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $request['status'] = 1; // [0: pending, 1: approved, 2: rejected]
            $res = KegiatanPerusahaanModel::updateData($kegiatan_id, $request);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? 'Kegiatan berhasil diapprove.' : 'Kegiatan gagal diapprove.'
            ]);
        }

        return redirect('/');
    }

    public function reject(Request $request, $id, $kegiatan_id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $request['status'] = 2; // [0: pending, 1: approved, 2: rejected]
            $request['keterangan'] = $request->reason;
            unset($request['reason']);
            $res = KegiatanPerusahaanModel::updateData($kegiatan_id, $request);

            return response()->json([
                'stat' => $res,
                'mc' => $res, // close modal
                'msg' => ($res) ? 'Kegiatan berhasil direject.' : 'Kegiatan gagal direject.'
            ]);
        }

        return redirect('/');
    }
}
