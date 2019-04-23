<?php

namespace App\Validator\DataCollectors;

interface CollectFormDataInterface {
    
    public function collectDatasetFromForm();
    
    public function preparingFormDataset($recordDataset);
    
    public function normalizeFormDataset($recordDataset);
    
    public function preparingCurrentRecordDataset();
}
