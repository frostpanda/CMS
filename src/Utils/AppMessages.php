<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\RequestStack;

class AppMessages {

    private $formMessage;

    function __construct(RequestStack $requestStack) {
        $this->formMessage = $requestStack->getCurrentRequest()->getSession()->getFlashBag();
    }

    public function displayInformationMessage(string $informationMessage) {
        $this->formMessage->add('info', $informationMessage);
    }

    public function displayWarningMessage(string $warningMessage) {
        $this->formMessage->add('warning', $warningMessage);
        return false;
    }

    public function displayErrorMessage(string $errorMessage) {
        $this->formMessage->add('danger', $errorMessage);
        return false;
    }

    public function displaySuccessMessage(string $successMessage) {
        $this->formMessage->add('success', $successMessage);
        return true;
    }

}
