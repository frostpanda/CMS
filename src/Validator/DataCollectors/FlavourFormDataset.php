<?php

namespace App\Validator\DataCollectors;

use App\Validator\DataCollectors\CollectFormDataInterface;

class FlavourFormDataset implements CollectFormDataInterface {

    public function preparingFormDataset($flavourFormDataset) {
        $normalizeDatasetResult = $this->normalizeFormDataset($flavourFormDataset);

        if ($normalizeDatasetResult) {
            $this->newFlavour['name'] = $flavourFormDataset->getName();

            return true;
        } else {
            return false;
        }
    }

    public function normalizeFormDataset($flavourFormDataset) {
        if ($flavourFormDataset) {
            $normalizeFlavourName = trim(htmlspecialchars($flavourFormDataset->getName()));

            $flavourFormDataset->setName($normalizeFlavourName);

            return true;
        } else {
            return false;
        }
    }

    public function preparingCurrentRecordDataset() {
        if ($this->currentFlavourObject) {
            $this->currentFlavour['name'] = $this->currentFlavourObject->getName();
        }
    }

    public function collectDatasetFromForm() {
        $this->preparingCurrentRecordDataset();

        return $this->preparingFormDataset($this->formHandler->manualValidation($this->flavourForm));
    }

}
