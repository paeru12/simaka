<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Ruangan extends Model
{
    protected $table = 'ruangans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama'
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
    public function qrkelas()
    {
        return $this->hasOne(QrKelas::class, 'ruangan_id');
    }
}
