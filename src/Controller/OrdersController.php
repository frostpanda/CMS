<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Forms\OrderForm;
use App\Utils\DatabaseHandler;
use App\Utils\UrlHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrdersController extends AbstractController {

    public function generateOrderListPage() {
        $orderList = $this->getDoctrine()->getRepository(Orders::class)->findBy(['deleted' => null]);

        return $this->render('cms/panel/orders/list.html.twig', array(
                    'pageTitle' => 'Order list',
                    'orderList' => $orderList
        ));
    }

    public function generateOrderDetailPage(UrlHandler $urlHandler, $orderNumber) {
        $orderEntity = $this->getDoctrine()->getRepository(Orders::class)->findOneBy(['order_number' => $orderNumber, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($orderEntity)) {
            return $this->redirectToRoute('order_list');
        }

        $editOrderForm = $this->createForm(OrderForm::class, $orderEntity, ['disabled' => true, 'submitLabel' => 'Modify order']);

        return $this->render('cms/panel/orders/details.html.twig', array(
                    'pageTitle' => 'Order detail',
                    'previousPageUrl' => 'order_list',
                    'editOrder' => 'active',
                    'dropdownOrder' => 'style=display:block;',
                    'form' => $editOrderForm->createView(),
                    'order' => $orderEntity,
        ));
    }

    public function generateEditOrderPage(DatabaseHandler $databaseHandler, Request $request, UrlHandler $urlHandler, $orderNumber) {
        $orderEntity = $this->getDoctrine()->getRepository(Orders::class)->findOneBy(['order_number' => $orderNumber, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($orderEntity)) {
            return $this->redirectToRoute('order_list');
        }

        $editOrderForm = $this->createForm(OrderForm::class, $orderEntity, ['submitLabel' => 'Modify order']);

        $editOrderForm->handleRequest($request);

        if ($editOrderForm->isSubmitted() && $editOrderForm->isValid()) {
            if ($orderEntity->getShippingMethod() == 'standard') {
                $orderEntity->setShippingCost($this->getParameter('standard_shipping_cost'));
            } else {
                $orderEntity->setShippingCost($this->getParameter('express_shipping_cost'));
            }
            
            $databaseHandler->modifyRecord($orderEntity);

            return $this->redirectToRoute('order_list');
        }

        return $this->render('cms/panel/orders/edit.html.twig', array(
                    'pageTitle' => 'Modify order',
                    'previousPageUrl' => 'order_list',
                    'editOrder' => 'active',
                    'dropdownOrder' => 'style=display:block;',
                    'order' => $orderEntity,
                    'form' => $editOrderForm->createView(),
        ));
    }

    public function deleteOrder(DatabaseHandler $databaseHandler, UrlHandler $urlHandler, $orderNumber) {
        $orderEntity = $this->getDoctrine()->getRepository(Orders::class)->findOneBy(['order_number' => $orderNumber, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($orderEntity)) {
            return $this->redirectToRoute('order_list');
        }

        $orderedProducts = $orderEntity->getOrderedProducts();

        foreach ($orderedProducts as $orderedProduct) {
            $orderedProduct->setDeleted(new \DateTime('now'));
        }

        $databaseHandler->deleteRecord($orderEntity);

        return $this->redirectToRoute('order_list');
    }

//    public function generateEditProductOrderPage($orderNumber, $productID) {
//        
//    }
//
//    public function deleteProductFromOrder($orderNumber, $productID) {
//        
//    }
}
