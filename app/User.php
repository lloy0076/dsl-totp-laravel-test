<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the API token.
     *
     * @param bool $clearOthers
     * @param string $name
     * @return string
     * @throws \Exception
     */
    public function getApiToken($keepOthers = true, $name = 'lloy0076')
    {
        if (!$keepOthers) {
            $didDelete = $this->tokens()->delete();

            if (!$didDelete) {
                throw new \Exception('Failed to clear other tokens.');
            }
        }

        $token = $this->createToken($name);

        return $token->plainTextToken;
    }
}
