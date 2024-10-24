<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Outlink extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'forwhich_user_url',
        'website_id',
        'website_url',
        'website_niche',
        'chat_id',
        'chat_status',
        'website_description',
        'acceptedby_from',
        'acceptedby_to',
        'seen',
        'status'
    ];

}
