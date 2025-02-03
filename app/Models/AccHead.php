<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccHead extends Model
{
    protected $table = 'ac_head';
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'id',
    ];
}
