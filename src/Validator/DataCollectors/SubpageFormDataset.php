<?php

namespace App\Validator\DataCollectors;

use App\Validator\DataCollectors\CollectFormDataInterface;

class SubpageFormDataset implements CollectFormDataInterface {

    public function preparingFormDataset($subpageFromDataset) {
        if ($this->normalizeFormDataset($subpageFromDataset)) {
            $this->newSubpage['name'] = $subpageFromDataset->getName();
            $this->newSubpage['url'] = $subpageFromDataset->getUrl();
            $this->newSubpage['code'] = $subpageFromDataset->getDescription();

            return true;
        } else {
            return false;
        }
    }

    public function normalizeFormDataset($subpageFromDataset) {
        if ($subpageFromDataset) {
            $normalizeSubpageName = trim(htmlspecialchars($subpageFromDataset->getName()));
            $normalizeSubpageUrl = trim(htmlspecialchars($subpageFromDataset->getUrl()));

            $subpageFromDataset->setName($normalizeSubpageName);
            $subpageFromDataset->setUrl($normalizeSubpageUrl);

            return true;
        } else {
            return false;
        }
    }

    public function preparingCurrentRecordDataset() {
        if ($this->currentSubpageObject) {
            $this->currentSubpage['name'] = $this->currentSubpageObject->getName();
            $this->currentSubpage['url'] = $this->currentSubpageObject->getUrl();
            $this->currentSubpage['code'] = $this->currentSubpageObject->getDescription();
        }
    }

    public function collectDatasetFromForm() {
        $this->preparingCurrentRecordDataset();

        return $this->preparingFormDataset($this->formHandler->manualValidation($this->subpageForm));
    }

}
