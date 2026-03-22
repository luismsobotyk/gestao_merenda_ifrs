<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// --- MODIFICAÇÃO 1: IMPORTAR AS CLASSES DO LDAPRECORD ---
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
// -------------------------------------------------------

// --- MODIFICAÇÃO 2: IMPLEMENTAR A INTERFACE LdapAuthenticatable ---
class User extends Authenticatable implements LdapAuthenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    // --- MODIFICAÇÃO 3: USAR O TRAIT AuthenticatesWithLdap ---
    // Este trait diz ao Laravel: "Não verifique a senha localmente, use o LDAP"
    use HasFactory, Notifiable, AuthenticatesWithLdap;
    // -------------------------------------------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'guid',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
