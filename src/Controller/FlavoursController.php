<?php

namespace App\Controller;

use App\Entity\Flavours;
use App\Forms\FlavourForm;
use App\Utils\DatabaseHandler;
use App\Utils\UrlHandler;
use App\Validator\FlavourValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FlavoursController extends AbstractController {

    public function generateFlavourListPage() {
        $flavourList = $this->getDoctrine()->getRepository(Flavours::class)->findBy(['deleted' => NULL]);

        return $this->render('cms/panel/flavours/list.html.twig', array(
                    'pageTitle' => 'Flavour list',
                    'flavourList' => $flavourList,
        ));
    }

    public function generateEditFlavourPage(FlavourValidation $flavourValiation, DatabaseHandler $databaseHandler, UrlHandler $urlHandler, $flavourID) {
        $flavourEntity = $this->getDoctrine()->getRepository(Flavours::class)->findOneBy(['id' => $flavourID, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($flavourEntity)) {
            return $this->redirectToRoute('flavour_list');
        }

        $editFlavourForm = $this->createForm(FlavourForm::class, $flavourEntity);

        if ($flavourValiation->validateForm($editFlavourForm, $flavourEntity)) {
            if ($databaseHandler->modifyRecord($flavourEntity)) {
                return $this->redirectToRoute('flavour_list');
            }
        }

        return $this->render('/cms/panel/flavours/form.html.twig', array(
                    'pageTitle' => 'Edit flavour',
                    'previousPageUrl' => 'flavour_list',
                    'editFlavour' => 'active',
                    'dropdownFlavour' => 'style=display:block;',
                    'form' => $editFlavourForm->createView()
        ));
    }

    public function deleteFlavour(DatabaseHandler $databaseHandler, $flavourID) {
        $databaseHandler->deleteRecord($this->getDoctrine()->getRepository(Flavours::class)->findOneBy(['id' => $flavourID, 'deleted' => null]));

        return $this->redirectToRoute('flavour_list');
    }

}
