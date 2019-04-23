<?php

namespace App\Validator;

use App\Utils\FormHandler;
use App\Validator\Constraints\VariableValidators;
use App\Validator\DataCollectors\VariableFormDataset;

class VariableValidation extends VariableFormDataset {

    protected $currentConfigVariable;
    protected $formHandler;
    protected $newConfigVariable;
    protected $variableForm;
    private $variableValidators;

    function __construct(FormHandler $formHandler, VariableValidators $variableValidators) {
        $this->formHandler = $formHandler;
        $this->variableValidators = $variableValidators;
    }

    private function validateVariableNameField() {
        $variableNameFieldLabel = 'Variable name';

        if ($this->variableValidators->validateStringLength($this->newConfigVariable['tag'], $variableNameFieldLabel, 3) === false) {
            return false;
        }

        if ($this->variableValidators->validateVariableFieldContent($this->newConfigVariable['tag'], $variableNameFieldLabel) === false) {
            return false;
        }

        if ($this->variableValidators->validateIfTagExist($this->newConfigVariable['tag'], $this->currentConfigVariable['tag']) === false) {
            return false;
        }

        return true;
    }

    private function validateVariableValueField() {
        $variableValueFieldLabel = 'Variable value';

        if ($this->variableValidators->validateStringLength($this->newConfigVariable['value'], $variableValueFieldLabel, 3) === false) {
            return false;
        }

        if ($this->variableValidators->validateVariableFieldContent($this->newConfigVariable['value'], $variableValueFieldLabel) === false) {
            return false;
        }

        return true;
    }

    public function preliminaryValidation() {
        if ($this->variableValidators->validateIfFieldsEmpty($this->newConfigVariable) === false) {
            return false;
        }

        if ($this->variableValidators->validateIfFieldsModified($this->newConfigVariable, $this->currentConfigVariable) === false) {
            return false;
        }

        return true;
    }

    public function validateFormFields() {
        $validateVariableNameFieldResult = $this->validateVariableNameField();
        $validateVariableValueFieldResult = $this->validateVariableValueField();

        if ($validateVariableNameFieldResult && $validateVariableValueFieldResult) {
            return true;
        } else {
            return false;
        }
    }

    public function validateForm(object $variableForm, object $currentVariableDataset) {
        $this->variableForm = $variableForm;
        $this->currentConfigVariableObject = $currentVariableDataset;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }

        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
