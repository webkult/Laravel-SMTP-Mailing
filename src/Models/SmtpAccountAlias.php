<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Webkult\LaravelSmtpMailing\Contracts\SmtpAccountAliasModelContract;

/**
 * Class SmtpAccountAlias
 *
 * @package Webkult\LaravelSmtpMailing\Models
 *
 * @property int $id
 * @property string $from_email
 * @property int $smtp_credential_id
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property-read SmtpCredential $smtpCredential
 */
class SmtpAccountAlias extends Model implements SmtpAccountAliasModelContract
{
    use HasFactory;
    use HasTimestamps;

    protected $table = 'smtp_account_aliases';

    protected $fillable = [
        'from_email',
        'smtp_credential_id',
    ];

    protected static function booted(): void
    {
        static::saving(static function ($model) {
            $model->from_email = strtolower((string)$model->from_email);
        });
    }

    public function smtpCredential(): BelongsTo
    {
        return $this->belongsTo(SmtpCredential::class);
    }
}
