<?php

namespace App\Models;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'deadline'
    ];

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
