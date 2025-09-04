<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class QrKelas extends Model
{
    protected $table = 'qr_kelas';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'ruangan_id', 'token', 'aktif', 'file'
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

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'ruangan_id');
    }
}
