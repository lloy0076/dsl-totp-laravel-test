<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Session;

class SessionStorageRepository implements StorageRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function getProvisioningUri(): ?string
    {
        return Session::get('provisioning-uri');
    }

    /**
     * @inheritDoc
     */
    public function setProvisioningUri(string $provisioningUri): void
    {
        Session::put('provisioning-uri', $provisioningUri);
    }

    /**
     * @inheritDoc
     */
    public function getSecret(): ?string
    {
        return Session::get('secret');
    }

    /**
     * @inheritDoc
     */
    public function setSecret(string $secret): void
    {
        Session::put('secret', $secret);
    }

    /**
     * @inheritDoc
     */
    public function getToken(): ?string
    {
        return Session::get('token');
    }

    /**
     * @inheritDoc
     */
    public function setToken(string $token): void
    {
        Session::put('token', $token);
    }

    /**
     * @inheritDoc
     */
    public function forget(): void
    {
        $remove = ['token', 'secret', 'provisioning-uri'];

        foreach ($remove as $key) {
            Session::remove($key);
        }
    }
}