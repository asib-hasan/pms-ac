<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    protected $table = 'order_info';
    use HasFactory;

    protected $fillable = [
        'order_info_session_id',
        'order_info_track_no',
        'order_info_subtotal',
        'order_info_discount_type',
        'order_info_discount',
        'order_info_total',
        'order_info_due',
        'order_info_date',
        'order_info_status',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'order_info_id',
    ];
    protected $primaryKey = 'order_info_id';

    public function customer_wise_order_info(){
        return $this->belongsTo(CustomerWiseOrder::class, 'order_info_id', 'order_info_id');
    }
}
