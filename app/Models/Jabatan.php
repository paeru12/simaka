<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Jabatan extends Model
{
    protected $table = 'jabatans'; 
    public $incrementing = false; // non-integer ID
    protected $keyType = 'string';

    protected $fillable = [
        'id','jabatan', 'gapok', 'tunjangan' ];

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
