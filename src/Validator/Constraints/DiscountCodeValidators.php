<?php

namespace App\Validator\Constraints;

use App\Entity\DiscountCodes;

class DiscountCodeValidators extends CommonValidators {

    public function validateDiscountCodeNameField(string $discountCode, string $customErrorMessage = null): bool {
        $standardErrorMessage = 'Discount code contains forbiden charachters! Allowed characters: alphanumeric!';
        $pattern = $this->frameworkParameters->getParameter('discount_code_pattern');

        if (preg_match($pattern, $discountCode) !== 1) {
            return $this->appMessages->displayErrorMessage($customErrorMessage ? $customErrorMessage : $standardErrorMessage);
        } else {
            return true;
        }
    }

    public function validateIfDiscountCodeExist(string $newDiscountCode, string $currentDiscountCode = null): bool {
        if ($newDiscountCode == $currentDiscountCode) {
            return true;
        }

        $checkDiscountCode = $this->entityManager->getRepository(DiscountCodes::class)->getDiscountCodeID($newDiscountCode);
        
        if (count($checkDiscountCode) > 0) {
            return $this->appMessages->displayErrorMessage('Discount code already exist!');
        }

        return true;
    }

    public function validateDicountCodeValueType(int $discountValue): bool {
        if (is_float($discountValue)) {
            return $this->appMessages->displayErrorMessage('Discount value is not integer!');
        } else {
            return true;
        }
    }

    public function validateDiscountCodeValue(int $discountValue): bool {
        if ($discountValue <= 0) {
            return $this->appMessages->displayErrorMessage('Discount value out of range! Minimum discount value is 1!');
        }

        if ($discountValue > 99) {
            return $this->appMessages->displayErrorMessage('Discount value out of range! maximum discount value is 99!');
        }

        return true;
    }

    public function validateDiscountCodeExpiryDate(object $expiryDate): bool {
        $expiryDate = $expiryDate->format('Y-m-d');
        $currentDate = new \DateTime('now');
        $currentDate = $currentDate->format('Y-m-d');

        if ($expiryDate < $currentDate) {
            return $this->appMessages->displayErrorMessage('Expiry date for discount code must have current or further date!');
        } else {
            return true;
        }
    }

}
