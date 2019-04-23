<?php

namespace App\Controller;

use App\Basket\OrderHandler;
use App\Entity\Orders;
use App\Forms\OrderForm;
use App\Utils\FormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController {

    public function generateOrderPage(FormHandler $formHandler, OrderHandler $orderHandler, Request $request) {
        $newOrder = new Orders();

        $orderForm = $this->createForm(OrderForm::class, $newOrder);

        if ($formHandler->symfonyValidation($orderForm)) {
            $orderHandler->placeNewOrder($orderForm->getData());
            return $this->redirectToRoute('basket');
        }

        return $this->render('order/index.html.twig', array(
                    'form' => $orderForm->createView(),
        ));
    }

}
