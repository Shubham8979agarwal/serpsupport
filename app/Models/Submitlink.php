<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Submitlink extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'connection_type',
        'chat_id',
        'typeoflink',
        'outlink_on',
        'backlink_to',
        'anchor_text',
        'outlink_placed_on_your_website',
        'acceptedby_to',
        'acceptedby_from',
        'chat_status'
    ];

}
