<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajarans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'nama_mapel', 'gaji'
    ];
 
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'mapel_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'mapel_id');
    }
}
