<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmtpCredential extends Model
{
    use HasFactory;

    protected $table = 'smtp_credentials';

    protected $fillable = [
        'host',
        'port',
        'encryption',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function aliases(): HasMany
    {
        return $this->hasMany(SmtpAccountAlias::class);
    }

    protected function password(): Attribute
    {
        return Attribute::make(get: fn($value) => decrypt($value), set: fn($value) => ['password' => encrypt($value)]);
    }

    protected function casts(): array
    {
        return [
            'port' => 'integer',
        ];
    }

}

