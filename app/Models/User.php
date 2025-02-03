<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'admin';
    use HasFactory, Notifiable;


    protected $fillable = [
        'admin_name ',
        'admin_email',
        'admin_password',
        'admin_phone ',
        'admin_status',
        'admin_type',
        'admin_created_by',
        'admin_updated_by',
    ];

    protected $guarded = [
        'admin_id',
    ];

    protected $primaryKey = 'admin_id';
}
