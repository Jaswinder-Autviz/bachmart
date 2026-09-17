<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'type', 'subject_type', 'subject_id',
        'description', 'properties', 'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public static function log(
        string $type,
        string $description,
        ?Model $subject = null,
        ?array $properties = null
    ): void {
        static::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
        ]);
    }
}
