<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'user_id',
    'nickname',
    'avatar',
    'birthday'
])]

class Profile extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function get_avatar_url()
    {
        return $this->avatar ? Storage::disk('s3')->url($this->avatar) : asset(config('filesystems.default_avatar'));
    }
}
