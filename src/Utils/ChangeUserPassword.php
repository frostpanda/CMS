<?php

namespace App\Utils;

use App\Entity\Administrator;
use App\Utils\AppMessages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class ChangeUserPassword {

    private $entityManager;
    private $appMessages;
    private $loggedUser;
    private $userNewPassword;
    private $passwordEncoder;

    function __construct(EntityManagerInterface $entityManager, AppMessages $appMessages, Security $security, UserPasswordEncoderInterface $passwordEncoder) {
        $this->entityManager = $entityManager;
        $this->appMessages = $appMessages;
        $this->loggedUser = $security->getUser();
        $this->passwordEncoder = $passwordEncoder;
    }

    private function normalizeUserPassword() {
        $normalizedPassword = trim(htmlspecialchars($this->userNewPassword));

        return $normalizedPassword;
    }

    private function encodeUserPassword() {
        $encodedPassword = $this->passwordEncoder->encodePassword($this->loggedUser, $this->normalizeUserPassword());

        return $encodedPassword;
    }

    private function updateUserPassword() {
        try {
            $loggedAdministrator = $this->entityManager->getRepository(Administrator::class)->findOneBy(['email' => $this->loggedUser->getUsername()]);

            $loggedAdministrator
                    ->setPassword($this->encodeUserPassword())
                    ->setModified(new \DateTime('now'))
            ;

            $this->entityManager->persist($loggedAdministrator);
            $this->entityManager->flush();

            $this->appMessages->displaySuccessMessage('Password changed!');
        } catch (Exception $ex) {
            $this->appMessages->displayErrorMessage('Error occured during changing password! If error still occurs, contact with Administrator!');
        }
    }

    public function changePassword(string $newUserPassword) {
        $this->userNewPassword = $newUserPassword;

        return $this->updateUserPassword();
    }

}
