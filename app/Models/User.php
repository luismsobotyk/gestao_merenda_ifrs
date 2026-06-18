<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasFactory, Notifiable, AuthenticatesWithLdap;

    protected $fillable = [
        'name',
        'username', // Mapeado para o sAMAccountName no config do LdapRecord
        'email',
        'password',
        'guid',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class)->orderBy('login_date', 'desc')->orderBy('login_time', 'desc');
    }

    public static function isSuperAdmin($user): bool
    {
        if (!$user) return false;
        $username = '';

        if ($user instanceof \LdapRecord\Models\Model) {
            $username = $user->getFirstAttribute('samaccountname');
        } else {
            $username = $user->username;
        }

        $adminsString = config('sisgem.admin_ldap_username', '');
        $adminsArray = array_filter(array_map('trim', explode(',', $adminsString)));

        return in_array($username, $adminsArray);
    }
}
