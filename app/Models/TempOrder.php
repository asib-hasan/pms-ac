<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempOrder extends Model
{
    protected $table = 'temp_order';
    use HasFactory;

    protected $fillable = [
        'temp_order_session_id',
        'temp_order_item_id',
        'temp_order_item_name',
        'temp_order_qty',
        'temp_order_item_buy_price',
        'temp_order_item_sell_price',
        'temp_order_item_expire_date',
        'temp_order_total',
        'temp_order_profit',
    ];

    protected $guarded = [
        'temp_order_id',
    ];

    protected $primaryKey = 'temp_order_id';

    #Relations
    public function item_info(){
        return $this->belongsTo(ItemInfo::class, 'temp_order_item_id','item_id');
    }
}
