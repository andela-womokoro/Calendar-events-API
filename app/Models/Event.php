<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'date',
        'time',
        'location',
        'created_by',
    ];

    /**
     * Get the invitations for this event.
     */
    public function invitation()
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the user this event belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
