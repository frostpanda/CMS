<?php

namespace App\Validator\Constraints;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordValidators extends CommonValidators {

    public function validateCurrentPassword(UserPasswordEncoderInterface $passwordEncoder, object $loggedUser, string $loggedUserCurrentPassword): bool {
        if ($passwordEncoder->isPasswordValid($loggedUser, $loggedUserCurrentPassword) === false) {
            return $this->appMessages->displayErrorMessage('Current password is incorrect!');
        }

        return true;
    }

    public function validateIfOldAndNewPasswordEquals(string $currentPassword, string $newPassword): bool {
        if ($currentPassword == $newPassword) {
            return $this->appMessages->displayErrorMessage('New password is the same as old one! Change it!');
        }

        return true;
    }

    public function validateNewPasswordString(string $newPassword): bool {
        $passwordPattern = $this->frameworkParameters->getParameter('password_pattern');

        if (preg_match($passwordPattern, $newPassword) !== 1) {
            return $this->appMessages->displayErrorMessage('Password contains forbidden charachters! Requirements: alphanumeric characters with at least 2 numbers!');
        }

        return true;
    }

    public function validateIfNewAndConfirmPasswordEquals(string $newPassword, string $confirmPassword): bool {

        if ($newPassword !== $confirmPassword) {
            return $this->appMessages->displayErrorMessage('Passwords are not the same!');
        }

        return true;
    }

}
