<?php

namespace App\Validator\DataCollectors;

use App\Validator\DataCollectors\CollectFormDataInterface;

class VariableFormDataset implements CollectFormDataInterface {

    private function normalizeField($fieldToNormalize) {
        $normalizedField = trim(htmlspecialchars($fieldToNormalize));

        return $normalizedField;
    }

    public function preparingFormDataset($variableFormDataset) {
        $normalizeDatasetResult = $this->normalizeFormDataset($variableFormDataset);

        if ($normalizeDatasetResult) {
            $this->newConfigVariable['tag'] = $variableFormDataset->getTag();
            $this->newConfigVariable['value'] = $variableFormDataset->getVal();

            return true;
        } else {
            return false;
        }
    }

    public function normalizeFormDataset($variableFormDataset) {
        if ($variableFormDataset) {
            $variableFormDataset->setTag($this->normalizeField($variableFormDataset->getTag()));
            $variableFormDataset->setVal($this->normalizeField($variableFormDataset->getVal()));

            return true;
        } else {
            return false;
        }
    }

    public function preparingCurrentRecordDataset() {
        if ($this->currentConfigVariableObject) {
            $this->currentConfigVariable['tag'] = $this->currentConfigVariableObject->getTag();
            $this->currentConfigVariable['value'] = $this->currentConfigVariableObject->getVal();
        }
    }

    public function collectDatasetFromForm() {
        $this->preparingCurrentRecordDataset();
        $variableDatasetPreparingResult = $this->preparingFormDataset($this->formHandler->manualValidation($this->variableForm));

        return $variableDatasetPreparingResult;
    }

}
