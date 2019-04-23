<?php

namespace App\Validator;

use App\Validator\DataCollectors\ChangePasswordFormDataset;

class PasswordValidation extends ChangePasswordFormDataset {

    private function validatePasswordField() {
        if ($this->passwordValidators->validateCurrentPassword($this->passwordEncoder, $this->loggedUserObject, $this->passwords['currentPassword']) === false) {
            return false;
        }

        return true;
    }

    private function validateNewPasswordField() {
        $newPasswordFieldLabel = 'New Password';
        if ($this->passwordValidators->validateStringLength($this->passwords['newPassword'], $newPasswordFieldLabel) === false) {
            return false;
        }

        if ($this->passwordValidators->validateNewPasswordString($this->passwords['newPassword']) === false) {
            return false;
        }

        if ($this->passwordValidators->validateIfOldAndNewPasswordEquals($this->passwords['currentPassword'], $this->passwords['newPassword']) === false) {
            return false;
        }

        return true;
    }

    private function validateConfirmPasswordField() {
        if ($this->passwordValidators->validateIfNewAndConfirmPasswordEquals($this->passwords['newPassword'], $this->passwords['confirmPassword']) === false) {
            return false;
        }

        return true;
    }

    private function preliminaryValidation() {
        if ($this->passwordValidators->validateIfFieldsEmpty($this->passwords) === false) {
            return false;
        }

        return true;
    }

    private function validateFormFields() {
        if ($this->validatePasswordField() === false) {
            return false;
        }

        if ($this->validateNewPasswordField() === false) {
            return false;
        }

        if ($this->validateConfirmPasswordField() === false) {
            return false;
        }

        return true;
    }

    public
            function validateForm($changePasswordForm) {
        $this->changePasswordForm = $changePasswordForm;

        if ($this->collectDatasetFromForm() === false) {
            return false;
        }

        if ($this->preliminaryValidation() === false) {
            return false;
        }

        return $this->validateFormFields();
    }

}
