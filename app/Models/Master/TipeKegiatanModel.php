<?php

namespace App\Models\Master;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipeKegiatanModel extends AppModel
{
    use SoftDeletes;

    protected $table = 'm_tipe_kegiatan';
    protected $primaryKey = 'tipe_kegiatan_id';

    protected static $_table = 'm_tipe_kegiatan';
    protected static $_primaryKey = 'tipe_kegiatan_id';

    protected $fillable = [
        'nama_kegiatan',
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
        //'App\Models\Master\EmployeeModel' => 'jabatan_id'
    ];
}
