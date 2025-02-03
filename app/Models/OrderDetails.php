<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    protected $table = 'order_details';
    use HasFactory;

    protected $fillable = [
        'order_details_order_info_id',
        'order_details_item_id',
        'order_details_item_name',
        'order_details_item_buy_price',
        'order_details_item_sell_price',
        'order_details_item_qty',
        'order_details_item_expire_date',
        'order_details_item_profit',
        'order_details_date',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'order_details_id',
    ];

    protected $primaryKey = 'order_details_id';


    #item info
    public function item_info(){
        return $this->belongsTo(ItemInfo::class, 'order_details_item_id','item_id');
    }
}
