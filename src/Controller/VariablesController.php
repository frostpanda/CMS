<?php

namespace App\Controller;

use App\Entity\Variables;
use App\Forms\VariableForm;
use App\Utils\DatabaseHandler;
use App\Utils\UrlHandler;
use App\Validator\VariableValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VariablesController extends AbstractController {

    public function generateVariableListPage() {
        $productVariables = $this->getDoctrine()->getRepository(Variables::class)->findBy(['deleted' => null]);

        return $this->render('cms/panel/variables/list.html.twig', array(
                    'pageTitle' => 'Variable list',
                    'variables' => $productVariables,
        ));
    }

    public function generateEditVariablePage(DatabaseHandler $databaseHandler, UrlHandler $urlHandler, VariableValidation $variableValidation, $variableTag) {
        $variableEntity = $this->getDoctrine()->getRepository(Variables::class)->findOneBy(['tag' => $variableTag, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($variableEntity)) {
            return $this->redirectToRoute('variable_list');
        }

        $editVariableForm = $this->createForm(VariableForm::class, $variableEntity, ['submitLabel' => 'Modify variable']);

        if ($variableValidation->validateForm($editVariableForm, $variableEntity)) {
            if ($databaseHandler->modifyRecord($variableEntity)) {
                return $this->redirectToRoute('variable_list');
            }
        }

        return $this->render('cms/panel/variables/form.html.twig', array(
                    'pageTitle' => 'Modify variable',
                    'previousPageUrl' => 'subpage_list',
                    'pageConfiguration' => 'active',
                    'dropdownPageConfiguration' => 'style=display:block;',
                    'form' => $editVariableForm->createView(),
        ));
    }

    public function deleteVariable(DatabaseHandler $databaseHandler, $variableTag) {
        $databaseHandler->deleteRecord($this->getDoctrine()->getRepository(Variables::class)->findOneBy(['tag' => $variableTag, 'deleted' => null]));

        return $this->redirectToRoute('variable_list');
    }

}
