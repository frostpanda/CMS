<?php

namespace App\Validator\Constraints;

use App\Entity\Variables;

class VariableValidators extends CommonValidators {

    public function validateVariableFieldContent(string $variableFormFieldValue, string $variableFormFieldName) {
        $pattern = $this->frameworkParameters->getParameter('variable_form_pattern');

        if (preg_match($pattern, $variableFormFieldValue) !== 1) {
            return $this->appMessages->displayErrorMessage($variableFormFieldName . ' field contains forbiddent characters! ');
        }

        return true;
    }

    public function validateIfTagExist(string $newVariableTag, string $currentVariableTag) {
        if ($newVariableTag == $currentVariableTag) {
            return true;
        }

        $checkExistanceTagName = $this->entityManager->getRepository(Variables::class)->findBy(['tag' => $newVariableTag]);

        if (count($checkExistanceTagName) > 0) {
            return $this->appMessages->displayErrorMessage('This variable already exist! Choose another one!');
        }

        return true;
    }

}
