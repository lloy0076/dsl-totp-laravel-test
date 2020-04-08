<?php


namespace App\Repositories;


interface StorageRepositoryContract
{
    /**
     * @return string|null
     */
    public function getProvisioningUri(): ?string;

    /**
     * @param string $provisioningUri
     */
    public function setProvisioningUri(string $provisioningUri): void;

    /**
     * @return string|null
     */
    public function getSecret(): ?string;

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void;

    /**
     * @return string|null
     */
    public function getToken(): ?string;

    /**
     * @param string $token
     */
    public function setToken(string $token): void;

    /**
     */
    public function forget(): void;
}