<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInfo extends Model
{
    protected $table = 'item';
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'item_category',
        'item_company',
        'item_quantity',
        'item_rack_no',
        'item_buy_price',
        'item_sell_price',
        'item_expire_date',
        'item_reorder_level',
        'item_status',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'item_id',
    ];

    protected $primaryKey = 'item_id';


    #item category info

    public function item_category_info(){
        return $this->belongsTo(Categories::class, 'item_category_id','item_category_id');
    }

    #item company info

    public function item_company_info(){
        return $this->belongsTo(Companies::class, 'item_company_id','item_company_id');
    }
}
