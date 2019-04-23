<?php

namespace App\Validator;

use App\Utils\FormHandler;
use App\Validator\Constraints\FlavourValidators;
use App\Validator\DataCollectors\FlavourFormDataset;

class FlavourValidation extends FlavourFormDataset {

    protected $currentFlavourObject;
    protected $flavourForm;
    protected $formHandler;
    protected $newFlavour;
    protected $currentFlavour;
    private $flavourValidators;

    function __construct(FlavourValidators $flavourValidators, FormHandler $formHandler) {
        $this->flavourValidators = $flavourValidators;
        $this->formHandler = $formHandler;
    }

    private function validateFlavourNameField() {
        $flavourNameField = 'Flavour name';
        if ($this->flavourValidators->validateStringLength($this->newFlavour['name'], $flavourNameField, 4) === false) {
            return false;
        }

        if ($this->flavourValidators->validateNameFieldContent($this->newFlavour['name'], $flavourNameField) === false) {
            return false;
        }

        if ($this->flavourValidators->validateIfFlavourExist($this->newFlavour['name'], $this->currentFlavour['name']) === false) {
            return false;
        }

        return true;
    }

    private function preliminaryValidation() {
        if ($this->flavourValidators->validateIfFieldsEmpty($this->newFlavour) === false) {
            return false;
        }

        if ($this->flavourValidators->validateIfFieldsModified($this->currentFlavour, $this->newFlavour) === false) {
            return false;
        }

        return true;
    }

    private function validateFormFields() {
        return $this->validateFlavourNameField();
    }

    public function validateForm(object $flavourForm, object $currentFlavourObject = null) {
        $this->flavourForm = $flavourForm;
        $this->currentFlavourObject = $currentFlavourObject;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }

        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
