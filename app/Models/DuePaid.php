<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuePaid extends Model
{
    protected $table = 'due_paid';
    use HasFactory;

    protected $fillable = [
        'dp_customer_id',
        'dp_amount',
        'dp_date',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'dp_id',
    ];

    protected $primaryKey = 'dp_id';

    public function admin_info(){
        return $this->belongsTo(User::class, 'created_by','admin_id');
    }
}
