<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInfo extends Model
{
    protected $table = 'store';
    use HasFactory;

    protected $fillable = [
        'store_title',
        'store_name',
        'store_email',
        'store_phone',
        'store_address'
    ];

    protected $guarded = [
        'store_id',
    ];
}
