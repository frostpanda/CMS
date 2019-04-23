<?php

namespace App\Validator\Constraints;

class ProductValidators extends CommonValidators {

    public function validateIfProductUrlExist(array $newTagName, ?array $currentTagName) {
        if ($newTagName == $currentTagName) {
            return true;
        }

        $checkTagName = $this->entityManager->getRepository(Variables::class)->findBy(['tag' => $newTagName]);

        if (count($checkTagName) > 0) {
            return $this->appMessages->displayErrorMessage('Tag name exist! Choose another one!');
        }

        return true;
    }

}
