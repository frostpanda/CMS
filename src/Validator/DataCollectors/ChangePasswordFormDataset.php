<?php

namespace App\Validator\DataCollectors;

use App\Utils\FormHandler;
use App\Validator\Constraints\PasswordValidators;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class ChangePasswordFormDataset {

    protected $passwordEncoder;
    protected $passwordValidators;
    protected $changePasswordForm;
    protected $passwords;
    protected $loggedUserObject;
    private $formHandler;

    function __construct(FormHandler $formHandler, PasswordValidators $passwordValidators, Security $security, UserPasswordEncoderInterface $passwordEncoder) {
        $this->formHandler = $formHandler;
        $this->loggedUserObject = $security->getUser();
        $this->passwordEncoder = $passwordEncoder;
        $this->passwordValidators = $passwordValidators;
    }

    private function normalizeField($fieldToNormalize) {
        $normalizedField = trim(htmlspecialchars($fieldToNormalize));

        return $normalizedField;
    }

    private function preparingFormDataset($changePasswordFormDataset) {
        if ($this->normalizeFormDataset($changePasswordFormDataset)) {
            $this->passwords['currentPassword'] = $changePasswordFormDataset['currentPassword'];
            $this->passwords['newPassword'] = $changePasswordFormDataset['newPassword'];
            $this->passwords['confirmPassword'] = $changePasswordFormDataset['confirmPassword'];

            return true;
        } else {
            return false;
        }
    }

    private function normalizeFormDataset(&$changePasswordFormDataset) {
        if ($changePasswordFormDataset) {
            $changePasswordFormDataset['currentPassword'] = $this->normalizeField($changePasswordFormDataset['currentPassword']);
            $changePasswordFormDataset['newPassword'] = $this->normalizeField($changePasswordFormDataset['newPassword']);
            $changePasswordFormDataset['confirmPassword'] = $this->normalizeField($changePasswordFormDataset['confirmPassword']);

            return true;
        } else {
            return false;
        }
    }

    public function collectDatasetFromForm() {
        $changePasswordFormDataset = $this->formHandler->manualValidation($this->changePasswordForm);

        return $this->preparingFormDataset($changePasswordFormDataset);
    }

}
