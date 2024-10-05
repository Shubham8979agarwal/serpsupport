<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Trial extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'trials';
    protected $guarded = array();
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'traial_days',
        'trail_status',
        'trail_description'
    ];
}
