<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'course_id', 'meeting_name', 'topic'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
