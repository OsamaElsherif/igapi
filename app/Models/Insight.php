<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insight extends Model
{
    protected $fillable = [
        'reach',
        'impressions',
        'story_id'
    ];

    use HasFactory;
}
