<?php

namespace MonthlyCloud\Laravel\Traits;

use Illuminate\Support\Facades\Crypt;

trait IsMonthlyCloudUser
{
    /**
     * Set cloud access token.
     *
     * @param string $accessToken 
     * @return self
     */
    public function setAccessToken($accessToken)
    {
        $this->access_token = Crypt::encryptString($accessToken);

        return $this;
    }

    /**
     * Get cloud access token.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        if (!empty($this->access_token)) {
            return Crypt::decryptString($this->access_token);
        }

        return null;
    }

    /**
     * Apply data from cloud user to current model.
     * 
     * For example set name equal to cloud label.
     * 
     * @param object $userData
     * @return self
     */
    public function applyUserData($userData)
    {
        $this->name = $userData->label;

        return $this;
    }
}
