<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Course;
use App\Models\Revision;
use App\Enums\ModelStatus;


class Source extends Model
{
    use HasFactory;

    /**
     * TODO: Change it to a separate entity
     */
    public const CHANNELS = ['youtube', 'online-plataform', 'presential-plataform', 'undemy', 'university'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'name', 'description', 'channel', 'user_id', 'status' ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ModelStatus::class
    ];

    public function revision()
    {
        return $this->morphOne(Revision::class, 'parent');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
