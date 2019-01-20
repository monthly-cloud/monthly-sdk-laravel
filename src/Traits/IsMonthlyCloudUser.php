<?php

namespace MonthlyCloud\Laravel\Traits;

use Illuminate\Support\Facades\Crypt;
use MonthlyCloud\Sdk\Builder;

trait IsMonthlyCloudUser
{
    private $cloud;

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

    /**
     * Get cloud builder.
     *
     * @return MonthlyCloud\Sdk\Builder
     */
    public function cloud()
    {
        if (empty($this->cloud)) {
            $this->cloud = new Builder(
                $this->getAccessToken(),
                config('monthlycloud.api_url')
            );
            $this->cloud->setCache(new \MonthlyCloud\Laravel\Cache(config('monthlycloud.cache_store')));
        }

        return $this->cloud;
    }
}
