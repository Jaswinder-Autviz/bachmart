<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (!$setting) return $default;

            return match($setting->type) {
                'boolean' => (bool) $setting->value,
                'integer' => (int) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });
    }

    public static function set(string $key, mixed $value): void
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting:{$key}");
    }

    public static function group(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }
}
