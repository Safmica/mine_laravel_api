<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'meeting_id', 'user_id', 'filename', 'filepath'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}