<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CmsController extends AbstractController {

    public function generatePanelLandingPage() {
        return $this->render('cms/panel/dashboard/index.html.twig', array(
                    'pageTitle' => 'Dashboard'
        ));
    }

}
