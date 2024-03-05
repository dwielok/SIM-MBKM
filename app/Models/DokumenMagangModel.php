<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenMagangModel extends Model
{
    use SoftDeletes;

    protected $table = 't_dokumen_magang';
    protected $primaryKey = 'dokumen_magang_id';

    protected static $_table = 't_dokumen_magang';
    protected static $_primaryKey = 'dokumen_magang_id';

    protected $fillable = [
        'mahasiswa_id',
        'magang_id',
        'dokumen_magang_nama',
        'dokumen_magang_file',
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
