<?php

namespace App\Controller;

use App\Entity\LoginHistory;
use App\Forms\PasswordForm;
use App\Utils\ChangeUserPassword;
use App\Validator\PasswordValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController {

    public function generateSettingsPage(ChangeUserPassword $changeUserPassword, PasswordValidation $passwordValidation) {
        $userLoginHistory = $this->getDoctrine()->getManager()->getRepository(LoginHistory::class)->findLoginHistoryByID($this->getUser()->getId(), 20);

        $changePasswordForm = $this->createForm(PasswordForm::class);

        if(isset($_POST[$changePasswordForm->getName()]['changePassword'])){
            if ($passwordValidation->validateForm($changePasswordForm)) {
                $changeUserPassword->changePassword($changePasswordForm->get('newPassword')->getData());
            }
            return $this->redirectToRoute('user_settings');
        }

        return $this->render('/cms/panel/settings/index.html.twig', array(
                    'form' => $changePasswordForm->createView(),
                    'loginHistory' => $userLoginHistory,
        ));
    }

}
