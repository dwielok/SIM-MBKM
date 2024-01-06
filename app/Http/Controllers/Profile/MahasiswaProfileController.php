<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\KategoriUsaha;
use App\Models\Master\MahasiswaModel;
use App\Models\View\MahasiswaView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MahasiswaProfileController extends Controller
{

    public function __construct(){
        $this->menuCode  = 'STUDENT.PROFILE';
        $this->menuUrl   = url('student/profile');     // set URL untuk menu ini
        $this->menuTitle = 'Profil Mahasiswa';                       // set nama menu
        $this->viewPath  = 'profile.mahasiswa.';         // untuk menunjukkan direktori view. Diakhiri dengan tanda titik
    }

    public function index(){
        $this->authAction('read');
        $this->authCheckDetailAccess();

        // untuk set breadcrumb pada halaman web
        $breadcrumb = [
            'title' => $this->menuTitle,
            'list'  => ['Data Profile']
        ];

        // untuk set aktif menu pada sidebar
        $activeMenu = [
            'l1' => 'profile',              // menu aktif untuk level 1, berdasarkan class yang ada di sidebar
            'l2' => null,              // menu aktif untuk level 2, berdasarkan class yang ada di sidebar
            'l3' => null               // menu aktif untuk level 3, berdasarkan class yang ada di sidebar
        ];

        // untuk set konten halaman web
        $page = [
            'url' => $this->menuUrl,
            'title' => $this->menuTitle
        ];

        $mahasiswa = MahasiswaView::find(getMahasiswaID());

        return (!$mahasiswa)? $this->showPageNotFound() :
            view($this->viewPath . 'index')
            ->with('breadcrumb', (object) $breadcrumb)
            ->with('activeMenu', (object) $activeMenu)
            ->with('page', (object) $page)
            ->with('allowAccess', $this->authAccessKey())
            ->with('mhs', $mahasiswa);
    }


    public function update(Request $request){
        $this->authAction('update', 'json');
        if($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $user = Auth::user();
            $rules = [
                'mahasiswa_email' => ['required', 'email:rfc,dns,filter', 'max:50', Rule::unique('s_user', 'email')->ignore($user->user_id, 'user_id')],
                'mahasiswa_name' => 'required|string|max:50',
                'mahasiswa_phone' => 'required|numeric|digits_between:8,15',
                'mahasiswa_gender' => 'required|in:L,P',
                'mahasiswa_tahun'	=> 'required|integer|min:2019|max:2050',
                'kelas' => 'required|string|max:5',
                'ortu_nama' => 'required|string|max:50',
                'ortu_hp' => 'required|numeric|digits_between:8,15',
                'url_fb' => 'required|url|max:255',
                'url_instagram' => 'required|url|max:255',
                'url_twitter' => 'required|url|max:255',
                'url_linkedin' => 'required|url|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'stat'     => false,
                    'mc'       => false, // close modal
                    'msg'      => 'terjadi kesalahan',
                    'msgField' => $validator->errors()
                ]);
            }

            if ($user) {
                try {
                    $user->email = $request->mahasiswa_email;
                    $user->name = $request->mahasiswa_name;
                    $user->updated_by = $user->user_id;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();

                    $res = MahasiswaModel::updateData(getMahasiswaID(), $request);

                    return response()->json([
                        'stat'     => true,
                        'mc'       => true, // close modal
                        'msg'      => $this->getMessage('update.success')
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'stat'     => false,
                        'mc'       => false, // close modal
                        'msg'      => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'stat'     => false,
                'mc'       => false, // close modal
                'msg'      => $this->getMessage('data.notfound')
            ]);
        }

        return redirect('/');
    }

    public function update_password(Request $request){
        $this->authAction('update', 'json');
        if($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {

            $user = Auth::user();

            $rules = [
                'password_old' => ['required', function ($attribute, $value, $fail) use ($user){
                    if (!Hash::check($value, $user->password))
                        $fail('The ' . $attribute . ' is invalid.');
                }],
                'password' => ['required', 'confirmed', 'min:6', 'different:password_old'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'stat'     => false,
                    'mc'       => false, // close modal
                    'msg'      => 'terjadi kesalahan',
                    'msgField' => $validator->errors()
                ]);
            }

            if ($user) {
                try {
                    $user->password = Hash::make($request->password);
                    $user->updated_by = $user->user_id;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();

                    return response()->json([
                        'stat'     => true,
                        'mc'       => true, // close modal
                        'msg'      => $this->getMessage('update.success')
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'stat'     => false,
                        'mc'       => false, // close modal
                        'msg'      => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'stat'     => false,
                'mc'       => false, // close modal
                'msg'      => $this->getMessage('data.notfound')
            ]);
        }

        return redirect('/');
    }


    public function update_avatar(Request $request){
        $this->authAction('update', 'json');
        if($this->authCheckDetailAccess() !== true) return $this->authCheckDetailAccess();

        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'image' => 'required|image',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'stat'     => false,
                    'mc'       => false, // close modal
                    'msg'      => 'terjadi kesalahan',
                    'msgField' => $validator->errors()
                ]);
            }

            $user = Auth::user();
            if ($user) {
                try {

                    if (!empty($user->avatar_dir)){
                        Storage::disk('public')->delete($user->avatar_dir);
                    }

                    $imgName = time() . '-' . uniqid() . '.' . $request->image->extension();
                    Storage::disk('public')->put('avatar/' . $imgName, $request->file('image')->get());

                    $user->avatar_url = asset(Storage::url('avatar/' . $imgName));
                    $user->avatar_dir = 'avatar/' . $imgName;
                    $user->updated_by = $user->user_id;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();

                    return response()->json([
                        'stat'     => true,
                        'mc'       => true, // close modal
                        'msg'      => $this->getMessage('update.success'),
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'stat'     => false,
                        'mc'       => false, // close modal
                        'msg'      => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'stat'     => false,
                'mc'       => false, // close modal
                'msg'      => $this->getMessage('data.notfound')
            ]);
        }

        return redirect('/');
    }
}
