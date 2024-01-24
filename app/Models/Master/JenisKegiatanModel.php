<?php

namespace App\Models\Master;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisKegiatanModel extends AppModel
{
    use SoftDeletes;

    protected $table = 'm_jenis_kegiatan';
    protected $primaryKey = 'jenis_kegiatan_id';

    protected static $_table = 'm_jenis_kegiatan';
    protected static $_primaryKey = 'jenis_kegiatan_id';

    protected $fillable = [
        'jenis_program_id',
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

    //relation to periode and prodi
    public function jenis_program()
    {
        return $this->belongsTo(JenisProgramModel::class, 'jenis_program_id');
    }
}
