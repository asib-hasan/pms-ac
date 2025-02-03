<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    protected $table = 'item_company';
    use HasFactory;

    protected $fillable = [
        'item_company_name',
        'item_company_status',
    ];


    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'item_company_id',
    ];
}
