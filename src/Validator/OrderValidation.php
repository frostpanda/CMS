<?php

namespace App\Validator;

use App\Utils\FormHandler;
use App\Validator\Constraints\OrderValidators;
use App\Validator\DataCollectors\OrderFormDataset;

class OrderValidation extends OrderFormDataset implements ValidationInterface {

    protected $orderForm;
    protected $currentOrderObject;
    protected $newOrder;
    protected $formHandler;
    private $orderValidators;

    function __construct(OrderValidators $orderValidators, FormHandler $formHandler) {
        $this->orderValidators = $orderValidators;
        $this->formHandler = $formHandler;
    }

    public function preliminaryValidation() {
        if ($this->orderValidators->validateIfFieldsEmpty($this->newOrder) === false) {
            return false;
        }

        return true;
    }

    private function validateFirstNameField() {
        $firstNameFieldLabel = 'First name';
        
        if ($this->orderValidators->validateStringLength($this->newOrder['name'], $firstNameFieldLabel, 4, 30) === false) {
            return false;
        }
        
    }

    private function validateLastNameField() {
        
    }

    private function validateCompanyNameField() {
        
    }

    private function validateCityNameField() {
        
    }

    private function validateZipCodeField() {
        
    }

    private function validateStreetNameField() {
        
    }

    private function validateHouseNumberField() {
        
    }

    private function validateFlatNumberField() {
        
    }

    private function validateEmailField() {
        
    }

    private function validatePhoneNumber() {
        
    }

    public function validateFormFields() {
        return true;
    }

    public function validateForm(object $orderForm, object $currentOrderObject = null) {
        $this->orderForm = $orderForm;
        $this->currentOrderObject = $currentOrderObject;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }


        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
