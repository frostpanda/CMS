<?php

namespace App\Validator\Constraints;

use App\Utils\AppMessages;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CommonValidators {

    protected $frameworkParameters;
    protected $appMessages;
    protected $entityManager;

    function __construct(ContainerInterface $containerInterface, AppMessages $appMessages, EntityManagerInterface $entityManagerInterface) {
        $this->entityManager = $entityManagerInterface;
        $this->frameworkParameters = $containerInterface;
        $this->appMessages = $appMessages;
    }

    private function checkIfFieldEmpty($field) {
        if (empty($field)) {
            return $this->appMessages->displayErrorMessage('Field or fields are empty!');
        }

        return true;
    }

    public function validateIfFieldsEmpty($formFields): bool {
        if (is_array($formFields)) {
            foreach ($formFields as $field) {
                return $this->checkIfFieldEmpty($field);
            }
        } else {
            return $this->checkIfFieldEmpty($formFields);
        }

        return true;
    }

    public function validateIfFieldsModified(array $newDataset, array $currentDataset = null): bool {
        if ($newDataset === $currentDataset) {
            return $this->appMessages->displayErrorMessage('Fields not modified! No need to update record!');
        } else {
            return true;
        }
    }

    public function validateStringLength(string $fieldValue, string $fieldName, int $minLength = 6, int $maxLength = 50): bool {
        $fieldLength = mb_strlen($fieldValue);

        if ($fieldLength < $minLength) {
            return $this->appMessages->displayErrorMessage($fieldName . ' must have at least ' . $minLength . ' characters!');
        }

        if ($fieldLength > $maxLength) {
            return $this->appMessages->displayErrorMessage($fieldName . ' is too long! Length cannot exceed ' . $maxLength . ' characters!');
        }

        return true;
    }

    public function validateNameFieldContent(string $fieldValue, string $fieldName): bool {
        $pattern = $this->frameworkParameters->getParameter('form_name_pattern');

        if (preg_match($pattern, $fieldValue) !== 1) {
            return $this->appMessages->displayErrorMessage($fieldName . ' field contains forbiden charachters! Only a-z characters are allowed!');
        }

        return true;
    }

    public function validateUrlField(string $urlField): bool {
        $pattern = $this->frameworkParameters->getParameter('form_url_pattern');

        if (preg_match($pattern, $urlField) !== 1) {
            return $this->appMessages->displayErrorMessage('Forbidden characters in URL field! Only a-z characters and dashes are allowed!');
        }

        return true;
    }

    public function validateIfUrlExist(string $entityName, string $newUrlValue, string $currentUrlValue = NULL) {
        if ($newUrlValue == $currentUrlValue) {
            return true;
        }

        $checkUrlInDatabase = $this->entityManager->getRepository($entityName)->findBy(['url' => $newUrlValue]);

        if (count($checkUrlInDatabase) > 0) {
            return $this->appMessages->displayErrorMessage('URL exist! Must be unique!');
        }

        return true;
    }

}
