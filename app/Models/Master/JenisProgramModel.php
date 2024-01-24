<?php

namespace App\Models\Master;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisProgramModel extends AppModel
{
    use SoftDeletes;

    protected $table = 'm_jenis_program';
    protected $primaryKey = 'jenis_program_id';

    protected static $_table = 'm_jenis_program';
    protected static $_primaryKey = 'jenis_program_id';

    protected $fillable = [
        'nama_program',
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
    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'periode_id');
    }

    public function prodi()
    {
        return $this->belongsTo(ProdiModel::class, 'prodi_id');
    }
}
