<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Plan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'plans';
    protected $guarded = array();
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plan_name',
        'plan_pricing',
        'plan_type',
        'plan_description',
        'plan_status'
    ];

}
