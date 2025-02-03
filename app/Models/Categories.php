<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'item_category';
    use HasFactory;

    protected $fillable = [
        'item_category_name',
        'item_category_status',
    ];

    protected $hidden = [
       'created_by',
       'updated_by',
       'created_at',
       'updated_at'
    ];
    protected $guarded = [
        'item_category_id',
    ];

    protected $primaryKey = 'item_category_id';
}
