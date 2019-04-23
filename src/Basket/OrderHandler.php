<?php

namespace App\Basket;

use App\Basket\BasketHandler;
use App\Entity\OrderedProducts;
use App\Entity\Orders;
use App\Entity\Products;
use App\Utils\FormHandler;
use App\Utils\AppMessages;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class OrderHandler {

    private $basketCookie;
    private $frameworkParameters;
    private $orderEntity;
    private $entityManager;
    private $appMessages;

    function __construct(BasketHandler $basketHandler, ContainerInterface $containerInterface, EntityManagerInterface $entityManager, FormHandler $formHandler, AppMessages $appMessages) {
        $this->basketCookie = $basketHandler;
        $this->entityManager = $entityManager;
        $this->frameworkParameters = $containerInterface;
        $this->formHandler = $formHandler;
        $this->appMessages = $appMessages;
    }

    private function setOrderEntity(object $orderEntity) {
        $this->orderEntity = $orderEntity;
    }

    private function setOrderDiscountCode() {
        $discountCode = $this->basketCookie->getDisountCodeEntity();

        if ($discountCode) {
            $discountCode->setCodesUsed($discountCode->getCodesUsed() + 1);
            $discountCode->setModified(new \DateTime('now'));
            $this->orderEntity->setDiscountCode($discountCode);
        }
    }

    private function setShippingCost() {
        if ($this->orderEntity->getShippingMethod() == 'standard') {
            $this->orderEntity->setShippingCost($this->frameworkParameters->getParameter('standard_shipping_cost'));
            return;
        }

        if ($this->orderEntity->getShippingMethod() == 'express') {
            $this->orderEntity->setShippingCost($this->frameworkParameters->getParameter('express_shipping_cost'));
            return;
        }
    }

    private function generateOrderNumber() {
        do {
            $orderNumber = random_int(100000000, 999999999);
        } while ($this->entityManager->getRepository(Orders::class)->checkOrderNumber($orderNumber) == false);

        return $orderNumber;
    }

    private function prepareOrderEntity($orderEntity) {
        $this->setOrderEntity($orderEntity);
        if ($this->orderEntity) {
            $this->orderEntity->setOrderNumber($this->generateOrderNumber());
            $this->orderEntity->setTotal($this->basketCookie->getTotalOrderPrice());
            $this->orderEntity->setCreated(new \DateTime('now'));
            $this->setOrderDiscountCode();
            $this->setShippingCost();
        } else {
            return false;
        }
    }

    private function boughtProduct($productID, $productQuantity) {
        $boughtProduct = new OrderedProducts();

        $productEntity = $this->entityManager->getRepository(Products::class)->findOneBy(['id' => $productID]);

        $boughtProduct->setOrder($this->orderEntity);
        $boughtProduct->setProduct($productEntity);
        $boughtProduct->setQuantity($productQuantity);

        if ($this->orderEntity->getDiscountCode()) {
            $discount = round(1 - ($this->orderEntity->getDiscountCode()->getDiscount() / 100), 2);
            $boughtProduct->setCost(round($productEntity->getPrice() * $productQuantity * $discount, 2));
        } else {
            $boughtProduct->setCost(round($productEntity->getPrice() * $productQuantity, 2));
        }
        $boughtProduct->setCreated(new \DateTime('now'));

        return $boughtProduct;
    }

    private function addOrderToDatabase() {
        try {
            $this->entityManager->persist($this->orderEntity);

            foreach ($this->basketCookie->getProductsInBasket() as $product) {
                $this->entityManager->persist($test = $this->boughtProduct($product['id'], $product['quantity']));
            }

            $this->entityManager->flush();

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function placeNewOrder(Orders $orderEntity) {
        $this->prepareOrderEntity($orderEntity);

        if ($this->addOrderToDatabase()) {
            $this->basketCookie->deleteBasketCookie();
            return $this->appMessages->displaySuccessMessage('You placed order!');
        } else {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

}
