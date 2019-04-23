<?php

namespace App\Validator\DataCollectors;

use App\Validator\DataCollectors\CollectFormDataInterface;

class CategoryFormDataset implements CollectFormDataInterface {

    private function normalizeField($fieldToNormalize) {
        $normalizedField = trim(htmlspecialchars($fieldToNormalize));

        return $normalizedField;
    }

    public function preparingFormDataset($categoryFormDataset) {
        if ($this->normalizeFormDataset($categoryFormDataset)) {
            $this->newCategory['name'] = $categoryFormDataset->getName();
            $this->newCategory['url'] = $categoryFormDataset->getUrl();

            return true;
        } else {
            return false;
        }
    }

    public function normalizeFormDataset($categoryFormDataset) {
        if ($categoryFormDataset) {
            $categoryFormDataset->setName($this->normalizeField($categoryFormDataset->getName()));
            $categoryFormDataset->setUrl($this->normalizeField($categoryFormDataset->getUrl()));

            return true;
        } else {
            return false;
        }
    }

    public function preparingCurrentRecordDataset() {
        if ($this->currentCategoryObject) {
            $this->currentCategory['name'] = $this->currentCategoryObject->getName();
            $this->currentCategory['url'] = $this->currentCategoryObject->getUrl();
        }
    }

    public function collectDatasetFromForm() {
        $this->preparingCurrentRecordDataset();

        return $this->preparingFormDataset($this->formHandler->manualValidation($this->categoryForm));
    }

}
