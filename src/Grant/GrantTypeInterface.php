<?php
/**
 * OAuth 2.0 Grant type interface.
 *
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */
namespace League\OAuth2\Server\Grant;

use League\Event\EmitterAwareInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Grant type interface.
 */
interface GrantTypeInterface extends EmitterAwareInterface
{
    /**
     * Set refresh token TTL.
     *
     * @param \DateInterval $refreshTokenTTL
     */
    public function setRefreshTokenTTL(\DateInterval $refreshTokenTTL);

    /**
     * Return the grant identifier that can be used in matching up requests.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Respond to an incoming request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface                  $request
     * @param \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface $responseType
     * @param \DateInterval                                             $accessTokenTTL
     *
     * @return \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    public function respondToRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    );

    /**
     * The grant type should return true if it is able to response to an authorization request
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    public function canRespondToAuthorizationRequest(ServerRequestInterface $request);

    /**
     * If the grant can respond to an authorization request this method should be called to validate the parameters of
     * the request.
     *
     * If the validation is successful an AuthorizationRequest object will be returned. This object can be safely
     * serialized in a user's session, and can be used during user authentication and authorization.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return AuthorizationRequest
     */
    public function validateAuthorizationRequest(ServerRequestInterface $request);

    /**
     * Once a user has authenticated and authorized the client the grant can complete the authorization request.
     * The AuthorizationRequest object's $userId property must be set to the authenticated user and the
     * $authorizationApproved property must reflect their desire to authorize or deny the client.
     *
     * @param \League\OAuth2\Server\RequestTypes\AuthorizationRequest $authorizationRequest
     *
     * @return \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    public function completeAuthorizationRequest(AuthorizationRequest $authorizationRequest);

    /**
     * The grant type should return true if it is able to respond to this request.
     *
     * For example most grant types will check that the $_POST['grant_type'] property matches it's identifier property.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    public function canRespondToAccessTokenRequest(ServerRequestInterface $request);

    /**
     * Set the client repository.
     *
     * @param \League\OAuth2\Server\Repositories\ClientRepositoryInterface $clientRepository
     */
    public function setClientRepository(ClientRepositoryInterface $clientRepository);

    /**
     * Set the access token repository.
     *
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function setAccessTokenRepository(AccessTokenRepositoryInterface $accessTokenRepository);

    /**
     * Set the scope repository.
     *
     * @param \League\OAuth2\Server\Repositories\ScopeRepositoryInterface $scopeRepository
     */
    public function setScopeRepository(ScopeRepositoryInterface $scopeRepository);

    /**
     * Set the path to the private key.
     *
     * @param \League\OAuth2\Server\CryptKey $privateKey
     */
    public function setPrivateKey(CryptKey $privateKey);

    /**
     * Set the path to the public key.
     *
     * @param \League\OAuth2\Server\CryptKey $publicKey
     */
    public function setPublicKey(CryptKey $publicKey);
}
