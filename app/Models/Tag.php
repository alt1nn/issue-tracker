<?php

namespace App\Models;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function issues()
    {
        return $this->belongsToMany(Issue::class, 'issue_tag');
    }
}