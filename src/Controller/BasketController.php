<?php

namespace App\Controller;

use App\Basket\BasketHandler;
use App\Forms\OrderDiscountCodeForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends AbstractController {

    public function generateBasketPage(BasketHandler $basketHandler, Request $request) {
        $basketCookie = $basketHandler->getValueOfBasketCookie();

        $discountCodeForm = $this->createForm(OrderDiscountCodeForm::class);

        $discountCodeForm->handleRequest($request);

        if ($discountCodeForm->getClickedButton() && $discountCodeForm->getClickedButton()->getName() === 'addDiscountCode') {
            if ($basketHandler->addDiscountCode($discountCodeForm->getData())) {
                return $this->redirectToRoute('basket');
            }
        }

        if ($discountCodeForm->getClickedButton() && $discountCodeForm->getClickedButton()->getName() === 'removeDiscountCode') {
            if ($basketHandler->removeDiscountCode()) {
                return $this->redirectToRoute('basket');
            }
        }

        $basketDataset = $basketHandler->prepareDatasetForTemplate();

        if ($discountCodeForm->getClickedButton() && $discountCodeForm->getClickedButton()->getName() === 'placeOrder') {
            if ($basketCookie['total'] > 9999.99) {
                $this->addFlash('danger', 'Order price exceeded! Order price cannot be higher than 9999');
                return $this->redirectToRoute('basket');
            }

            return $this->redirectToRoute('order_form');
        }

        return $this->render('basket/index.html.twig', array(
                    'form' => $discountCodeForm->createView(),
                    'basket' => $basketDataset,
        ));
    }

    public function removeProductFromBasket(BasketHandler $basketHandler, $productID) {
        $basketHandler->removeProduct($productID);

        return $this->redirectToRoute('basket');
    }

}
