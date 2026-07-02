<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueSetting extends Model
{
    protected $table = 'queue_settings';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
