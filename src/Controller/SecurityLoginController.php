<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityLoginController extends AbstractController {

    public function loginPage(AuthenticationUtils $authenticationUtils): Response {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('cms_landing_page');
        } else {
            $authenticationError = $authenticationUtils->getLastAuthenticationError();

            $lastEnteredUsername = $authenticationUtils->getLastUsername();

            return $this->render('cms/login_page/index.html.twig', array(
                        'last_username' => $lastEnteredUsername,
                        'error' => $authenticationError
            ));
        }
    }

}
