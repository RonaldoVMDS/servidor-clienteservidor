<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Occurrence extends Model
{
    // ...
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registered_at',
        'local',
        'occurrence_type',
        'km',
        'user_id',
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->onDelete('set null');
    }
}

