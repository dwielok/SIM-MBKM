<?php

namespace App\Models\Master;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisMagangModel extends AppModel
{
    use SoftDeletes;

    protected $table = 'm_jenis_magang';
    protected $primaryKey = 'jenis_magang_id';

    protected static $_table = 'm_jenis_magang';
    protected static $_primaryKey = 'jenis_magang_id';

    protected $fillable = [
        'nama_magang',
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
