<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Kelas extends Model
{
    protected $table = 'kelass';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'kelas', 'rombel'
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
}
