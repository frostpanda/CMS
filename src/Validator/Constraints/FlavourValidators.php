<?php

namespace App\Validator\Constraints;

use App\Entity\Flavours;

class FlavourValidators extends CommonValidators {

    public function validateIfFlavourExist(string $newFlavourName, ?string $currentFlavourName) {
        if ($newFlavourName == $currentFlavourName) {
            return true;
        }

        $flavourNameDb = $this->entityManager->getRepository(Flavours::class)->checkIfFlavourExist($newFlavourName);

        if ($flavourNameDb) {
            $this->appMessages->displayErrorMessage('Flavour already exist in database!');
            return false;
        }

        return true;
    }

}
