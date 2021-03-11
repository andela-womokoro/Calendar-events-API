<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_sent',
        'event_id',
        'user_id',
        'created_by',
    ];

    
    /**
     * Get the event this invitation belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user this invitation belongs to.
     */
    public function event()
    {
        return $this->belongsTo(User::class);
    }
}
