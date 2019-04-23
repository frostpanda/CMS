<?php

namespace App\Validator\Constraints;

use App\Validator\Constraints\CommonValidators;

class OrderValidators extends CommonValidators {

    public function validateNameField(string $name) {
        $pattern = $this->frameworkParameters->getParameter('polish_names_pattern');

        if (preg_match($pattern, $name) !== 1) {
            return $this->appMessages->displayErrorMessage('Forbiden characters in name field!');
        }
    }

    public function validateSurnameField(string $surname) {
        $pattern = $this->frameworkParameters->getParameter('polish_names_pattern');

        if (preg_match($pattern, $surname) !== 1) {
            return $this->appMessages->displayErrorMessage('Forbiden characters in name field!');
        }
    }

}
