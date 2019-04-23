<?php

namespace App\Validator;

use App\Utils\FormHandler;
use App\Validator\Constraints\DiscountCodeValidators;
use App\Validator\DataCollectors\DiscountCodeFormDataset;

class DiscountCodeValidation extends DiscountCodeFormDataset implements ValidationInterface {

    protected $currentDiscountCode;
    protected $currentDiscountCodeObject;
    protected $discountCodeForm;
    protected $formHandler;
    protected $newDiscountCode;
    private $discountCodeValidators;

    function __construct(DiscountCodeValidators $discountCodeValidators, FormHandler $formHandler) {
        $this->discountCodeValidators = $discountCodeValidators;
        $this->formHandler = $formHandler;
    }

    public function validateDiscountCodeField() {
        $discountCodeFieldName = 'Discount code';
        if ($this->discountCodeValidators->validateStringLength($this->newDiscountCode['code'], $discountCodeFieldName) === false) {
            return false;
        }

        if ($this->discountCodeValidators->validateDiscountCodeNameField($this->newDiscountCode['code']) === false) {
            return false;
        }

        if ($this->discountCodeValidators->validateIfDiscountCodeExist($this->newDiscountCode['code'], $this->currentDiscountCode['code']) === false) {
            return false;
        }

        return true;
    }

    private function validateDiscountValueField() {
        if ($this->discountCodeValidators->validateDiscountCodeValue($this->newDiscountCode['discount']) === false) {
            return false;
        }

        return true;
    }

    private function validateExpiryDateField() {
        if ($this->discountCodeValidators->validateDiscountCodeExpiryDate($this->newDiscountCode['expiryDate']) === false) {
            return false;
        }

        return true;
    }

    public function preliminaryValidation() {
        if ($this->discountCodeValidators->validateIfFieldsEmpty($this->newDiscountCode) === false) {
            return false;
        }

        if ($this->currentDiscountCode) {
            return $this->discountCodeValidators->validateIfFieldsModified($this->currentDiscountCode, $this->newDiscountCode);
        }

        return true;
    }

    public function validateFormFields() {
        if ($this->validateDiscountCodeField() && $this->validateDiscountValueField() && $this->validateExpiryDateField()) {
            return true;
        } else {
            return false;
        }
    }

    public function validateForm(object $discountCodeForm, object $currentDiscountCodeObject = null) {
        $this->discountCodeForm = $discountCodeForm;
        $this->currentDiscountCodeObject = $currentDiscountCodeObject;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }

        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
