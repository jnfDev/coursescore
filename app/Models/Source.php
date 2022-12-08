<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Course;

class Source extends Model
{
    use HasFactory;

    /**
     * TODO: Change it to a separate entity
     */
    public const CHANNELS = ['youtube', 'online-plataform', 'presential-plataform', 'undemy', 'university'];

    protected $fillable = [ 'name', 'description', 'channel', 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
