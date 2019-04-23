<?php

namespace App\Controller;

use App\Entity\Subpages;
use App\Forms\SubpageForm;
use App\Utils\DatabaseHandler;
use App\Utils\UrlHandler;
use App\Validator\SubpageValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubpagesController extends AbstractController {

    public function generateSubpageListPage() {
        $subpageList = $this->getDoctrine()->getRepository(Subpages::class)->findBy(['deleted' => null]);

        return $this->render('cms/panel/subpages/list.html.twig', array(
                    'pageTitle' => 'Subpage list',
                    'subpageList' => $subpageList,
        ));
    }

    public function generateNewSubpagePage(SubpageValidation $subpageValidation, DatabaseHandler $databaseHandler) {
        $newSubpage = new Subpages();

        $newSubpageForm = $this->createForm(SubpageForm::class, $newSubpage);

        if ($subpageValidation->validateForm($newSubpageForm)) {
            if ($databaseHandler->insertNewRecord($newSubpage)) {
                return $this->redirectToRoute('subpage_list');
            }
        }

        return $this->render('cms/panel/subpages/form.html.twig', array(
                    'pageTitle' => 'New subpage',
                    'previousPageUrl' => 'subpage_list',
                    'form' => $newSubpageForm->createView(),
        ));
    }

    public function generateEditSubpagePage(DatabaseHandler $databaseHandler, SubpageValidation $subpageValidation, UrlHandler $urlHandler, $subpageID) {
        $subpageEntity = $this->getDoctrine()->getRepository(Subpages::class)->findOneBy(['id' => $subpageID]);

        if ($urlHandler->verifyUrlParameter($subpageEntity)) {
            return $this->redirectToRoute('subpage_list');
        }

        $editSubpageForm = $this->createForm(SubpageForm::class, $subpageEntity, ['submitLabel' => 'Modify subpage']);

        if ($subpageValidation->validateForm($editSubpageForm, $subpageEntity)) {
            if ($databaseHandler->modifyRecord($subpageEntity)) {
                return $this->redirectToRoute('subpage_list');
            }
        }

        return $this->render('cms/panel/subpages/form.html.twig', array(
                    'pageTitle' => 'Modify subpage',
                    'previousPageUrl' => 'subpage_list',
                    'editSubpage' => 'active',
                    'dropdownSubpage' => 'style=display:block;',
                    'form' => $editSubpageForm->createView()
        ));
    }

    public function deleteSubpage(DatabaseHandler $databaseHandler, $subpageID) {
        $databaseHandler->deleteRecord($this->getDoctrine()->getRepository(Subpages::class)->findOneBy(['id' => $subpageID, 'deleted' => null]));

        return $this->redirectToRoute('subpage_list');
    }

}
