<?php

namespace App\Security;

use App\Exception\ApiException;
use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly ApiTokenRepository $apiTokenRepo)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization')
            && str_starts_with($request->headers->get('Authorization'), 'Bearer ');
    }

    /**
     * @throws ApiException
     */
    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $tokenFromRequest = substr($authorizationHeader, 7);
        $tokenFromDatabase = $this->apiTokenRepo->findOneBy([
            'token' => $tokenFromRequest
        ]);
        if (!$tokenFromDatabase) {
            throw new ApiException('You are not authorized to use this api');
        }

        $userIdentifier = $tokenFromDatabase->getUser()?->getUserIdentifier();
        $userBadge = new UserBadge($userIdentifier);
        $passwordCredentials = new PasswordCredentials('123456');

        return new Passport($userBadge, $passwordCredentials);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;//return null if authentication is successful
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['error' => $exception->getMessage()]);
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
