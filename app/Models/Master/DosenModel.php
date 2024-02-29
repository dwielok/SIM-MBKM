<?php

namespace App\Models\Master;

use App\Models\AppModel;
use App\Models\Transaction\KuotaDosenModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DosenModel extends AppModel{

    use SoftDeletes;
    protected $table = 'm_dosen';
    protected $primaryKey = 'dosen_id';

    protected static $_table = 'm_dosen';
    protected static $_primaryKey = 'dosen_id';

    protected $fillable = [
        'dosen_nip',
        'dosen_nidn',
        'dosen_name',
        'dosen_email',
        'dosen_phone',
        'dosen_gender',
        'jabatan_id',
        'pangkat_id',
        'dosen_tahun',
        'sinta_id',
        'scholar_id',
        'scopus_id',
        'researchgate_id',
        'orcid_id',
        'user_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public function kuotaDosen()
    {
        return $this->hasOne(KuotaDosenModel::class, 'dosen_id', 'dosen_id'); // Atau hasMany jika satu dosen memiliki banyak kuota
    }

    protected static $cascadeDelete = false;   //  True: Force Delete from Parent (cascade)
    protected static $childModel = [
        //  Model => columnFK
        // 'App\Models\Master\DosenModel' => 'jurusan_id'
    ];
}