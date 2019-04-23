<?php

namespace App\Controller;

use App\Entity\DiscountCodes;
use App\Forms\DiscountCodeForm;
use App\Utils\DatabaseHandler;
use App\Utils\UrlHandler;
use App\Validator\DiscountCodeValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscountCodesController extends AbstractController {

    public function generateDiscountCodeListPage() {
        $discountCodeList = $this->getDoctrine()->getRepository(DiscountCodes::class)->findBy(['deleted' => NULL]);

        return $this->render('cms/panel/discount_codes/list.html.twig', array(
                    'pageTitle' => 'Discount code list',
                    'discountCodeList' => $discountCodeList,
        ));
    }

    public function generateNewDiscountCodePage(DatabaseHandler $databaseHandler, DiscountCodeValidation $discountCodeValidation) {
        $newDiscountCode = new DiscountCodes();

        $newDiscountCodeForm = $this->createForm(DiscountCodeForm::class, $newDiscountCode);

        $validationResult = $discountCodeValidation->validateForm($newDiscountCodeForm);

        if ($validationResult) {
            if ($databaseHandler->insertNewRecord($newDiscountCode)) {
                return $this->redirectToRoute('discount_code_list');
            }
        }

        return $this->render('cms/panel/discount_codes/form.html.twig', array(
                    'pageTitle' => 'Add discount code',
                    'previousPageUrl' => 'discount_code_list',
                    'form' => $newDiscountCodeForm->createView(),
        ));
    }

    public function generateEditDiscountCodePage(DatabaseHandler $databaseHandler, DiscountCodeValidation $discountCodeValidation, UrlHandler $urlHandler, $discountCodeID) {
        $discountCodeEntity = $this->getDoctrine()->getRepository(DiscountCodes::class)->findOneBy(['id' => $discountCodeID, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($discountCodeEntity)) {
            return $this->redirectToRoute('discount_code_list');
        }

        $editDiscountCodeForm = $this->createForm(DiscountCodeForm::class, $discountCodeEntity, ['submitLabel' => 'Modify discount code']);

        if ($discountCodeValidation->validateForm($editDiscountCodeForm, $discountCodeEntity)) {
            $databaseHandler->modifyRecord($discountCodeEntity);
            return $this->redirectToRoute('discount_code_list');
        }

        return $this->render('cms/panel/discount_codes/form.html.twig', array(
                    'editDiscountCode' => 'active',
                    'dropdownDiscountCode' => 'style=display:block;',
                    'pageTitle' => 'Modify discount code',
                    'previousPageUrl' => 'discount_code_list',
                    'form' => $editDiscountCodeForm->createView(),
        ));
    }

    public function deleteDiscountCode(DatabaseHandler $databaseHandler, $discountCodeID) {
        $databaseHandler->deleteRecord($this->getDoctrine()->getRepository(DiscountCodes::class)->findOneBy(['id' => $discountCodeID, 'deleted' => null]));

        return $this->redirectToRoute('discount_code_list');
    }

}
