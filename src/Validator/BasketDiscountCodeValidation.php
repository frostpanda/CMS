<?php

namespace App\Validator;

use App\Validator\Constraints\DiscountCodeValidators;

class BasketDiscountCodeValidation implements ValidationInterface {

    private $discountCodeValidators;

    function __construct(DiscountCodeValidators $discountCodeValidators) {
        $this->discountCodeValidators = $discountCodeValidators;
    }

    public function preliminaryValidation() {
        
    }

    public function validateFormFields() {
        
    }

    public function validateForm(object $form, object $currentData = null) {
        
    }

}
