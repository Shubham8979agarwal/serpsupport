<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Services\LinkService; // Import the LinkService class

class Website extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'website_id',
        'website_niche',
        'website_url',
        'chat_id',
        'website_description',
        'website_uploader_email'
    ];

    protected static function booted()
    {
        static::created(function ($website) {
            // Ensure that the user is authenticated
            if (Auth::check()) {
                // Check if this is the user's first website
                $userEmail = Auth::user()->email;
                if (Website::where('website_uploader_email', $userEmail)->count() === 1) {
                    // Use the service class
                    $linkService = app(LinkService::class);
                    $linkService->createBacklinks();
                    $linkService->createOutlinks();
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'website_uploader_email', 'email');
    }

}
