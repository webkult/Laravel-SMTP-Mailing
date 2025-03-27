<?php

declare(strict_types=1);

namespace Webkult\LaravelSmtpMailing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'port' => 'integer',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Optional: Falls du das Passwort verschlüsseln willst.
     * Aktivieren durch Verschlüsselung beim Setzen.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }

    public function getPasswordAttribute($value)
    {
        return decrypt($value);
    }

    public function aliases()
    {
        return $this->hasMany(SmtpAccountAlias::class);
    }

}

