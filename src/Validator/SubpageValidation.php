<?php

namespace App\Validator;

use App\Entity\Subpages;
use App\Utils\FormHandler;
use App\Validator\Constraints\SubpageValidators;
use App\Validator\DataCollectors\SubpageFormDataset;
use App\Validator\ValidationInterface;

class SubpageValidation extends SubpageFormDataset implements ValidationInterface {

    protected $subpageForm;
    protected $currentSubpageObject;
    protected $currentSubpage;
    protected $formHandler;
    protected $newSubpage;
    private $subpageValidators;

    function __construct(SubpageValidators $subpageValidators, FormHandler $formHandler) {
        $this->subpageValidators = $subpageValidators;
        $this->formHandler = $formHandler;
    }

    private function validateSubpageNameField() {
        $subpageNameFieldLabel = 'Subpage name';

        if ($this->subpageValidators->validateStringLength($this->newSubpage['name'], $subpageNameFieldLabel, 3) === false) {
            return false;
        }

        if ($this->subpageValidators->validateNameFieldContent($this->newSubpage['name'], $subpageNameFieldLabel) === false) {
            return false;
        }

        return true;
    }

    private function validateSubpageUrlField() {
        $subpageUrlFieldLabel = 'Subpage URL';

        if ($this->subpageValidators->validateStringLength($this->newSubpage['url'], $subpageUrlFieldLabel, 3) === false) {
            return false;
        }

        if ($this->subpageValidators->validateUrlField($this->newSubpage['url'], $subpageUrlFieldLabel) === false) {
            return false;
        }

        if ($this->subpageValidators->validateIfUrlExist(Subpages::class, $this->newSubpage['url'], $this->currentSubpage['url']) === false) {
            return false;
        }
        return true;
    }

    private function validateSubpageDescriptionField() {
        $subpageDescriptionFieldLabel = 'Subpage code';

        if ($this->subpageValidators->validateStringLength($this->newSubpage['code'], $subpageDescriptionFieldLabel, 10, 4048) === false) {
            return false;
        }

//        if ($this->subpageValidators->validateDescriptionFieldContent($this->newSubpage['code']) === false) {
//            return false;
//        }

        return true;
    }

    public function preliminaryValidation() {
        if ($this->subpageValidators->validateIfFieldsEmpty($this->newSubpage) === false) {
            return false;
        }

        if ($this->subpageValidators->validateIfFieldsModified($this->newSubpage, $this->currentSubpage) === false) {
            return false;
        }

        return true;
    }

    public function validateFormFields() {
        $subpageNameFieldValidationResult = $this->validateSubpageNameField();
        $subpageUrlFieldValidationResult = $this->validateSubpageUrlField();
        $subpageCodeFieldValidationResult = $this->validateSubpageDescriptionField();

        if ($subpageNameFieldValidationResult && $subpageUrlFieldValidationResult && $subpageCodeFieldValidationResult) {
            return true;
        } else {
            return false;
        }
    }

    public function validateForm(object $subpageForm, object $currentSubpageObject = null) {
        $this->currentSubpageObject = $currentSubpageObject;
        $this->subpageForm = $subpageForm;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }

        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
