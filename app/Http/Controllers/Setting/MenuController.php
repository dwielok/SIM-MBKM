<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Master\PeriodeModel;
use App\Models\Setting\MenuModel;
use App\Models\View\MenuView;
use App\Models\View\PeriodeRangeView;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class MenuController extends Controller
{

    public function __construct()
    {
        $this->menuCode  = 'SETTING.MENU';
        $this->menuUrl   = url('setting/menu');     // set URL untuk menu ini
        $this->menuTitle = 'Pengaturan - Menu';                      // set nama menu
        $this->viewPath  = 'setting.menu.';      // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index()
    {
        $this->authAction('read');
        $this->authCheckDetailAccess();

        // untuk set breadcrumb pada halaman web
        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Pengaturan', 'Menu']
        ];

        // untuk set aktif menu pada sidebar
        $activeMenu = [
            'l1' => 'setting',            // menu aktif untuk level 1, berdasarkan class yang ada di sidebar
            'l2' => 'setting-menu',    // menu aktif untuk level 2, berdasarkan class yang ada di sidebar
            'l3' => null                    // menu aktif untuk level 3, berdasarkan class yang ada di sidebar
        ];

        // untuk set konten halaman web
        $page = [
            'url' => $this->menuUrl,
            'title' => 'Daftar ' . $this->menuTitle
        ];

        $parent = MenuModel::whereNull('parent_id')
            ->selectRaw('menu_id as id, menu_code as code, menu_name as name')
            ->orderBy('order_no')
            ->get();

        return view($this->viewPath . 'index')
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('page', (object) $page)
            ->with('parent', $parent)
            ->with('allowAccess', $this->authAccessKey());
    }

    public function list(Request $request)
    {
        $this->authAction('read', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        $data  = MenuView::selectRaw('menu_id, menu_code, menu_name, menu_url, menu_level, order_no, is_active, parent_code')
            ->orderBy('order_no');

        if ($request->level != null) {
            $data->where('menu_level', $request->level);
        }

        if ($request->parent != null) {
            $data->where('parent_id', $request->parent);
        }

        return FacadesDataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function set_active(Request $request, $id)
    {
        $this->authAction('update', 'json');
        if ($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        // cek untuk Insert/Update/Delete harus via AJAX
        if ($request->ajax() || $request->wantsJson()) {

            // validasinya tidak usah dibuatkan class sendiri, biar disini saja
            // karena validasi untuk insert dan update bisa berbeda
            $rules = [
                'periode_id'      => 'required|numeric|between:202301,203012'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'stat'     => false,
                    'mc'       => false,            // jika diset true, maka respon json akan membuat popup modal menutup/close
                    'msg'      => 'Terjadi kesalahan.',
                    'msgField' => $validator->errors()
                ]);
            }

            // update data via function yg ada di model
            $res = PeriodeModel::updateData($id, $request);

            if ($res->status) {
                $periode_active = PeriodeModel::where('is_active', 1)
                    ->selectRaw('periode_id, periode_name')
                    ->first();

                session()->put('periode_active', $periode_active);

                $periode = PeriodeModel::all();
                session()->put('periode', $periode);
            }

            return response()->json([
                'stat' => $res->status,
                'msg' => $res->message
            ]);
        }

        return redirect('/');
    }
}
