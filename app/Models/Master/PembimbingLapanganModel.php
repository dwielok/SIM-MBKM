<?php

namespace App\Models\Master;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembimbingLapanganModel extends AppModel
{

    use SoftDeletes;
    protected $table = 'm_pembimbing_lapangan';
    protected $primaryKey = 'pembimbing_lapangan_id';

    protected static $_table = 'm_pembimbing_lapangan';
    protected static $_primaryKey = 'pembimbing_lapangan_id';

    protected $fillable = [
        'name_pembimbing_lapangan',
        'jabatan_pembimbing_lapangan',
        'tempat_industri_pembimbing_lapangan',
        'phone_pembimbing_lapangan',
        'email_pembimbing_lapangan',
        'user_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    protected static $cascadeDelete = false;   //  True: Force Delete from Parent (cascade)
    protected static $childModel = [
        //  Model => columnFK
        // 'App\Models\Master\DosenModel' => 'jurusan_id'
    ];
}
