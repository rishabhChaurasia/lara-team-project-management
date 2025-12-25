<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'email',
        'project_id',
        'invited_by',
        'role',
        'token',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
