<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Container\ContainerInterface;

class CookieHandler {

    private $cookieName;
    private $cookieValue;
    private $cookieExpireTime;
    private $request;
    private $frameworkParameters;

    function __construct(ContainerInterface $containerInterface, RequestStack $requestStack) {
        $this->frameworkParameters = $containerInterface;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function setCookieName(string $cookieName) {
        $this->cookieName = $cookieName;

        return $this;
    }

    public function setCookieValue(array $cookieValue) {
        $this->cookieValue = json_encode($cookieValue);

        return $this;
    }

    public function getCookieValue() {
        return json_decode($this->request->cookies->get($this->cookieName), TRUE);
    }

    public function setCookieExpiryDate(int $cookieExpiryDate) {
        $this->cookieExpireTime = $cookieExpiryDate * 60;

        return $this;
    }

    private function prepareCookie() {
        $cookie = new Cookie($this->cookieName, $this->cookieValue, time() + $this->cookieExpireTime, "/", "", $this->frameworkParameters->getParameter('cookie_secure'), $this->frameworkParameters->getParameter('cookie_http_only'), false, null);

        return $cookie;
    }

    public function createCookie() {
        $response = new Response();

        $response->headers->setCookie($this->prepareCookie());
        $response->send();

        return $response;
    }

    private function checkIfCookieExist() {
        if ($this->request->cookies->has($this->cookieName)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateCookie() {
        $this->cookieValue = $this->request->cookies->get($this->cookieName);
        $this->createCookie();

        return $this;
    }

    public function deleteCookie(string $cookieName) {
        $response = new Response();
        $response->headers->clearCookie($cookieName);
        $response->send();

        return $response;
    }

    public function handleCookie() {
        if ($this->checkIfCookieExist()) {
            $this->updateCookie();
        } else {
            $this->createCookie();
        }
        
        return $this;
    }

}
