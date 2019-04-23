<?php

namespace App\Validator\Constraints;

use App\Entity\Subpages;
use App\Validator\Constraints\CommonValidators;

class SubpageValidators extends CommonValidators {

    public function validateDescriptionFieldContent(string $desciptionFieldValue) {
        $pattern = $this->frameworkParameters->getParameter('wysiwig_pattern');

        if (preg_match($pattern, $desciptionFieldValue) !== 1) {
            return $this->appMessages->displayErrorMessage('Subpage code contains forbiden tags! <script> is not allowed!');
        } else {
            return true;
        }
    }

}
