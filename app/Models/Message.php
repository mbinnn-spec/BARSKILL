<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Interact with the message attribute.
     */
    protected function message(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::censor($value),
            set: fn ($value) => self::censor($value)
        );
    }

    /**
     * Censor bad words in text messages.
     */
    public static function censor($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Keep system formats like images and video calls untouched
        if (str_starts_with($text, '__IMAGE__:') || str_starts_with($text, '__VIDEO_CALL_ROOM__:')) {
            return $text;
        }

        // List of bad words (kata kasar)
        $badWords = [
            'anjing', 'babi', 'bangsat', 'kontol', 'memek', 'asu', 'goblok', 'tolol', 
            'fuck', 'shit', 'fak', 'jembut', 'jancok', 'pantek', 'perek', 'bajingan', 
            'pecun', 'lonte', 'pelacur', 'bgst', 'ngentot', 'ngentod', 'jmb'
        ];

        // Escape each word for regex safety
        $escapedWords = array_map(function($word) {
            return preg_quote($word, '/');
        }, $badWords);

        // Pattern using word boundaries, case-insensitive
        $pattern = '/\b(' . implode('|', $escapedWords) . ')\b/i';

        return preg_replace_callback($pattern, function($matches) {
            return str_repeat('*', strlen($matches[0]));
        }, $text);
    }
}