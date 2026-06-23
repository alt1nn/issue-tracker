<?php

namespace App\Models;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'deadline'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
