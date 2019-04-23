<?php

namespace App\Validator;

use App\Entity\Categories;
use App\Utils\FormHandler;
use App\Validator\Constraints\CategoryValidators;
use App\Validator\DataCollectors\CategoryFormDataset;

class CategoryValidation extends CategoryFormDataset implements ValidationInterface {

    protected $categoryForm;
    protected $currentCategoryObject;
    protected $currentCategory;
    protected $formHandler;
    protected $newCategory;
    private $categoryValidators;

    function __construct(CategoryValidators $categoryValidators, FormHandler $formHandler) {
        $this->categoryValidators = $categoryValidators;
        $this->formHandler = $formHandler;
    }

    private function validateCategoryNameField() {
        $categoryNameFieldLabel = 'Category name';

        if ($this->categoryValidators->validateStringLength($this->newCategory['name'], $categoryNameFieldLabel) === false) {
            return false;
        }

        if ($this->categoryValidators->validateNameFieldContent($this->newCategory['name'], $categoryNameFieldLabel) === false) {
            return false;
        }

        return true;
    }

    private function validateCategoryUrlField() {
        $categoryUrlFieldLabel = 'Category URL';

        if ($this->categoryValidators->validateStringLength($this->newCategory['url'], $categoryUrlFieldLabel, 4, 50) === false) {
            return false;
        }

        if ($this->categoryValidators->validateUrlField($this->newCategory['url']) === false) {
            return false;
        }

        if ($this->categoryValidators->validateIfUrlExist(Categories::class, $this->newCategory['url'], $this->currentCategory['url']) === false) {
            return false;
        }

        return true;
    }

    public function preliminaryValidation() {
        if ($this->categoryValidators->validateIfFieldsEmpty($this->newCategory) === false) {
            return false;
        }

        if ($this->categoryValidators->validateIfFieldsModified($this->newCategory, $this->currentCategory) === false) {
            return false;
        }

        return true;
    }

    public function validateFormFields() {
        $validateCategoryNameFieldResult = $this->validateCategoryNameField();
        $validateCategoryUrlFieldResult = $this->validateCategoryUrlField();

        if ($validateCategoryNameFieldResult && $validateCategoryUrlFieldResult) {
            return true;
        } else {
            return false;
        }
    }

    public function validateForm(object $categoryForm, object $currentCategoryDataset = null) {
        $this->categoryForm = $categoryForm;
        $this->currentCategoryObject = $currentCategoryDataset;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }

        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
