<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'activity',
        'user_id',
        'meeting_date',
        'meeting_time',
        'meeting_point',
        'start_point',
        'end_point',
        'members_count',
        'gender',
        'city',
        'status',
    ];

    protected $casts = [
        'meeting_point' => 'array',
        'start_point' => 'array',
        'end_point' => 'array',
    ];

    // an activity belong to one user 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
