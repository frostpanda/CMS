<?php

namespace App\Validator;

interface ValidationInterface {

    public function preliminaryValidation();

    public function validateFormFields();

    public function validateForm(object $form, object $currentData = null);
}
