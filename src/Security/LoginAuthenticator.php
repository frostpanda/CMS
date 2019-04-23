<?php

namespace App\Security;

use App\Entity\LoginHistory;
use App\Entity\Administrator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractFormLoginAuthenticator {

    use TargetPathTrait;

    private $entityManager;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request) {
        return 'cms_login_page' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request) {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
                Security::LAST_USERNAME,
                $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(Administrator::class)->findOneBy(['email' => $credentials['email']]);

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) {
        $passwordValidation = $this->passwordEncoder->isPasswordValid($user, $credentials['password']);

        return $passwordValidation;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $loginInformation['loginUserName'] = $request->request->get('email');
        $loginInformation['loginResult'] = 0;
        $loginInformation['loginIpAddress'] = $request->getClientIp();

        $this->entityManager->getRepository(LoginHistory::class)->insertLoginHistory($loginInformation);

        return new RedirectResponse($this->getLoginUrl());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        //if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
        //    return new RedirectResponse($targetPath);
        //}
        // For example : return new RedirectResponse($this->router->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);


        $loginInformation['loginUserName'] = $request->request->get('email');
        $loginInformation['loginResult'] = 1;
        $loginInformation['loginIpAddress'] = $request->getClientIp();

        $this->entityManager->getRepository(LoginHistory::class)->insertLoginHistory($loginInformation);

        return new RedirectResponse($this->router->generate('cms_landing_page'));
    }

    protected function getLoginUrl() {
        return $this->router->generate('cms_login_page');
    }

}
