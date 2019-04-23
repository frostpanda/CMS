<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\RequestStack;

class FormHandler {

    private $request;

    function __construct(RequestStack $request) {
        $this->request = $request->getCurrentRequest();
    }

    private function formRequestHandling($form) {
        $form->handleRequest($this->request);
    }

    public function manualValidation(object $form) {
        $this->formRequestHandling($form);

        if ($form->isSubmitted()) {
            return $form->getData();
        } else {
            return false;
        }
    }

    public function symfonyValidation(object $form) {
        $this->formRequestHandling($form);

        if ($form->isSubmitted() && $form->isValid()) {
            return $form->getData();
        } else {
            return false;
        }
    }

}
