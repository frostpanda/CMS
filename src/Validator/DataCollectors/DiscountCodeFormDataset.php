<?php

namespace App\Validator\DataCollectors;

use App\Validator\DataCollectors\CollectFormDataInterface;

class DiscountCodeFormDataset implements CollectFormDataInterface {

    public function preparingFormDataset($discountCodeFormDataset) {
        $normalizeDatasetResult = $this->normalizeFormDataset($discountCodeFormDataset);

        if ($normalizeDatasetResult) {
            $this->newDiscountCode['code'] = $discountCodeFormDataset->getCode();
            $this->newDiscountCode['discount'] = $discountCodeFormDataset->getDiscount();
            $this->newDiscountCode['expiryDate'] = $discountCodeFormDataset->getExpiryDate();

            return true;
        } else {
            return false;
        }
    }

    public function normalizeFormDataset($discountCodeFormDataset) {
        if ($discountCodeFormDataset) {
            $normalizeDiscountCode = trim(htmlspecialchars($discountCodeFormDataset->getCode()));

            $discountCodeFormDataset->setCode($normalizeDiscountCode);

            return true;
        } else {
            return false;
        }
    }

    public function preparingCurrentRecordDataset() {
        if ($this->currentDiscountCodeObject) {
            $this->currentDiscountCode['code'] = $this->currentDiscountCodeObject->getCode();
            $this->currentDiscountCode['discount'] = $this->currentDiscountCodeObject->getDiscount();
            $this->currentDiscountCode['expiryDate'] = $this->currentDiscountCodeObject->getExpiryDate();
        }
    }

    public function collectDatasetFromForm() {
        $this->preparingCurrentRecordDataset();
        $discountCodeDatasetFromForm = $this->formHandler->manualValidation($this->discountCodeForm);

        return $this->preparingFormDataset($discountCodeDatasetFromForm);
    }

}
