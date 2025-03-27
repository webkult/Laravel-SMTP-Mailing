<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpAccountAlias extends Model
{
    use HasFactory;

    protected $table = 'smtp_account_aliases';

    protected $fillable = [
        'from_email',
        'smtp_credential_id',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            $model->from_email = strtolower($model->from_email);
        });
    }

    public function smtpCredential()
    {
        return $this->belongsTo(SmtpCredential::class);
    }
}
