<?php

namespace App\Models\Transaction;

use App\Models\AppModel;
use App\Models\Master\DosenModel;
use App\Models\Master\ProdiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KuotaDosenModel extends AppModel
{

    use SoftDeletes;
    protected $table = 't_kuota_dosen';
    protected $primaryKey = 'kuota_dosen_id';

    protected static $_table = 't_kuota_dosen';
    protected static $_primaryKey = 'kuota_dosen_id';

    protected $fillable = [
        'dosen_id',
        'count_advisor_TI',
        'count_advisor_SIB',
        'count_advisor_PPLS'
    ];

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id', 'dosen_id');
    }

    protected static $cascadeDelete = false;   //  True: Force Delete from Parent (cascade)
    protected static $childModel = [
        //  Model => columnFK
        // 'App\Models\Master\DosenModel' => 'jurusan_id'
    ];
}
