<?php

namespace Seoda\Socialite\Apple;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use GuzzleHttp\ClientInterface;

use Seoda\Socialite\NotImplementedException;

class AppleProvider extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        'name',
        'email',
    ];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://appleid.apple.com/auth/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://appleid.apple.com/auth/token';
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code)
    {
        $response = parent::getAccessTokenResponse($code);
        $id_tokens = explode('.', $response['id_token']);

        return array_merge(
            $response,
            [
                'id_token_header' => json_decode(base64_decode(Arr::get($id_tokens, 0)), true),
                'id_token_payload' => json_decode(base64_decode(Arr::get($id_tokens, 1)), true),
                'id_token_signature' => Arr::get($id_tokens, 2),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject($response);

        return $user->setToken(Arr::get($response, 'access_token'))
                    ->setRefreshToken(Arr::get($response, 'refresh_token'))
                    ->setExpiresIn(Arr::get($response, 'expires_in'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        // This method is not implemeneted yet.
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id_token_payload']['sub'],
            'name' => $user['name'] ?? null,
            'email' => $user['email'] ?? null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
