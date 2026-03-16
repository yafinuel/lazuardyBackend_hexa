<?php

namespace App\Models;

use App\Shared\Enums\FileTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = 
    [
        'user_id',
        'name',
        'type',
        'path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => FileTypeEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
