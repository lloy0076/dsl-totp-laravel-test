<?php

namespace App\Repositories;

use App\Models\UserInfo;

class DataStorageRepository implements StorageRepositoryContract
{
    /**
     * Gets the user id.
     *
     * @return mixed
     */
    protected function userId() {
        return request()->user()->id ?? null;
    }

    /**
     * Get the record.
     *
     * @param null $userId
     * @return mixed
     * @throws \Exception
     */
    protected function getRecord($userId = null) {
        if (!$userId) {
            $userId = $this->userId();
        }

        if (!$userId) {
            return null;
        }

        return UserInfo::firstOrNew(['user_id' => $userId]);
    }

    /**
     * @inheritDoc
     */
    public function getProvisioningUri(): ?string
    {
        $userInfo = $this->getRecord();

        return $userInfo->provisioning_uri ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setProvisioningUri(string $provisioningUri): void
    {
        $userInfo = $this->getRecord();

        $userInfo->provisioning_uri = $provisioningUri;

        if (!$userInfo->save()) {
            throw new \Exception('Error saving value.');
        }
    }

    /**
     * @inheritDoc
     */
    public function getSecret(): ?string
    {
        $userInfo = $this->getRecord();

        return $userInfo->secret ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setSecret(string $secret): void
    {
        $userInfo = $this->getRecord();

        $userInfo->secret = $secret;
        if (!$userInfo->save()) {
            throw new \Exception('Error saving value.');
        }
    }

    /**
     * @inheritDoc
     */
    public function getToken(): ?string
    {
        $userInfo = $this->getRecord();

        return $userInfo->token ?? null;
    }

    /**
     * @inheritDoc
     */
    public function setToken(string $token): void
    {
        $userInfo = $this->getRecord();

        $userInfo->token = $token;
        if (!$userInfo->save()) {
            throw new \Exception('Error saving value.');
        }
    }

    /**
     * @inheritDoc
     */
    public function forget(): void
    {
        $userInfo = $this->getRecord();

        $remove = ['token', 'secret', 'provisioning_uri'];

        foreach ($remove as $index => $key) {
            $userInfo[$key] = null;
        }

        $userInfo->save();
    }
}