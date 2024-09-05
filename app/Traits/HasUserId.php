<?php

namespace App\Traits;

trait HasUserId
{
    public static function bootHasUserId(): void
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }
}
