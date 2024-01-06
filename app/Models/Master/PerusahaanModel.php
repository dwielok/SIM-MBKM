<?php

namespace App\Models\Master;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerusahaanModel extends AppModel
{
    use SoftDeletes;

    protected $table = 'm_perusahaan';
    protected $primaryKey = 'perusahaan_id';

    protected static $_table = 'm_perusahaan';
    protected static $_primaryKey = 'perusahaan_id';

    protected $fillable = [
        'nama_perusahaan',
        'user_id',
        'status',
        'logo',
        'kategori',
        'tipe_industri',
        'alamat',
        'provinsi_id',
        'kota_id',
        'profil_perusahaan',
        'website',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    protected static $cascadeDelete = false;   //  True: Force Delete from Parent (cascade)
    protected static $childModel = [
        //  Model => columnFK
        // 'App\Models\Master\DosenModel' => 'jurusan_id'
    ];
}
