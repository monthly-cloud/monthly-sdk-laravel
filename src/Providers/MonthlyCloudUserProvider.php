<?php

namespace MonthlyCloud\Laravel\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MonthlyCloud\Sdk\Builder;

class MonthlyCloudUserProvider extends EloquentUserProvider
{
    /**
     * Guzzle client.
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return;
        }
        if (! $this->retrieveModelByCredentials($credentials)) {
            if ($accessToken = $this->getAccessTokenByCredentials($credentials)) {
                $userData = $this->getUserDataByAccessToken($accessToken);
                $model = $this->createModel();
                foreach ($credentials as $key => $value) {
                    if (! Str::contains($key, 'password')) {
                        $model->{$key} = $value;
                    } else {
                        $model->password = Hash::make($value);
                    }
                }
                if (method_exists($model, 'applyUserData')) {
                    $model->applyUserData($userData);
                }

                $model->setAccessToken($accessToken);
                $model->save();

                return $model;
            }
            // Wrong crdentials.
            return;
        }

        return $this->retrieveModelByCredentials($credentials);
    }

    /**
     * Retrieve a user model by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveModelByCredentials($credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * Get headers used in api calls.
     *
     * @return array
     */
    private function getHeaders()
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        return $headers;
    }

    /**
     * Get user data by access token.
     *
     * @param  string  $accessToken
     * @return object
     */
    public function getUserDataByAccessToken($accessToken)
    {
        $builder = new Builder(
            $accessToken,
            $this->getApiUrl()
        );

        $userData = $builder
            ->endpoint('user')
            ->get()
            ->data;

        return $userData;
    }

    /**
     * Get access token by credentials or false in case of error.
     *
     * @param  array  $credentials
     * @return bool|string
     */
    public function getAccessTokenByCredentials(array $credentials)
    {
        if (empty($this->client)) {
            $this->client = new Client(['verify' => false]);
        }

        try {
            $this->response = $this->client->request(
                'POST',
                $this->getApiUrl().'login',
                [
                    'headers' => $this->getHeaders(),
                    'json' => $credentials,
                ]
            );
            $response = json_decode($this->response->getBody());
            if (empty($response->data->access_token)) {
                return false;
            }

            return $response->data->access_token;
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * Get monthly cloud api url.
     *
     * @return string
     */
    public function getApiUrl()
    {
        return config('monthlycloud.api_url');
    }
}
