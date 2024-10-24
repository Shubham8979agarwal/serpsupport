<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions'; // Make sure this is correct

    protected $fillable = [
        'user_id', 'stripe_subscription_id', 'status'
    ];

    // Add any relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
