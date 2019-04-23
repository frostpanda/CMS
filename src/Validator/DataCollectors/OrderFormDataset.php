<?php

namespace App\Validator\DataCollectors;

use App\Validator\DataCollectors\CollectFormDataInterface;

class OrderFormDataset implements CollectFormDataInterface {

    private function normalizeField($fieldToNormalize) {
        $normalizedField = trim(htmlspecialchars($fieldToNormalize));

        return $normalizedField;
    }

    public function normalizeFormDataset($orderFormDataset) {
        if ($orderFormDataset) {
            $orderFormDataset->setName($this->normalizeField($orderFormDataset->getName()));
            $orderFormDataset->setSurname($this->normalizeField($orderFormDataset->getSurname()));
            $orderFormDataset->setCity($this->normalizeField($orderFormDataset->getCity()));
            $orderFormDataset->setZip($this->normalizeField($orderFormDataset->getZip()));
            $orderFormDataset->setStreet($this->normalizeField($orderFormDataset->getStreet()));
            $orderFormDataset->setFlatNumber($this->normalizeField($orderFormDataset->getFlatNumber()));
            $orderFormDataset->setEmail($this->normalizeField($orderFormDataset->getEmail()));
            $orderFormDataset->setPhone($this->normalizeField($orderFormDataset->getPhone()));
            
            if ($orderFormDataset->getCompany()) {
                $orderFormDataset->setCompany($this->normalizeField($orderFormDataset->getCompany()));
            }
            
            if ($orderFormDataset->getHouse()) {
                $orderFormDataset->setHouse($this->normalizeField($orderFormDataset->getHouse()));
            }
            return true;
        } else {
            return false;
        }
    }

    public function preparingFormDataset($orderFormDataset) {
        if ($this->normalizeFormDataset($orderFormDataset)) {
            $this->newOrder['name'] = $orderFormDataset->getName();
            $this->newOrder['surname'] = $orderFormDataset->getSurname();
            $this->newOrder['city'] = $orderFormDataset->getCity();
            $this->newOrder['zip'] = $orderFormDataset->getZip();
            $this->newOrder['street'] = $orderFormDataset->getStreet();
            $this->newOrder['flatNumber'] = $orderFormDataset->getFlatNumber();
            $this->newOrder['email'] = $orderFormDataset->getEmail();
            $this->newOrder['phone'] = $orderFormDataset->getPhone();
            
            if ($orderFormDataset->getCompany()) {
                $this->newOrder['company'] = $orderFormDataset->getCompany();
            }

            if ($orderFormDataset->getHouse()) {
                $this->newOrder['house'] = $orderFormDataset->getHouse();
            }

            return true;
        } else {
            return false;
        }
    }

    public function preparingCurrentRecordDataset() {
        if ($this->currentOrderObject) {
            $this->newOrder['name'] = $this->currentOrderObject->getName();
            $this->newOrder['surname'] = $this->currentOrderObject->getSurname();
            $this->newOrder['company'] = $this->currentOrderObject->getCompany();
            $this->newOrder['city'] = $this->currentOrderObject->getCity();
            $this->newOrder['zip'] = $this->currentOrderObject->getZip();
            $this->newOrder['street'] = $this->currentOrderObject->getStreet();
            $this->newOrder['house'] = $this->currentOrderObject->getHouse();
            $this->newOrder['flatNumber'] = $this->currentOrderObject->getFlatNumber();
            $this->newOrder['email'] = $this->currentOrderObject->getEmail();
            $this->newOrder['phone'] = $this->currentOrderObject->getPhone();

            return true;
        } else {
            return false;
        }
    }

    public function collectDatasetFromForm() {
        if ($this->currentOrderObject) {
            $this->preparingCurrentRecordDataset();
        }

        return $this->preparingFormDataset($this->formHandler->manualValidation($this->orderForm));
    }

}
