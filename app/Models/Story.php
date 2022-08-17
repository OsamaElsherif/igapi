<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Report;
use App\Models\Insight;

class Story extends Model
{
    protected $fillable = [
        'story_id',
        'media_url',
        'media_type',
        'media_product_type',
        'thumbnail_url',
        'user_id',
        'report_id',
        'insight_id'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
    
    public function report() {
        return $this->hasOne(Report::class);
    }
    public function insight() {
        return $this->hasOne(Insight::class);
    }

    use HasFactory;
}
