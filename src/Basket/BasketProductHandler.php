<?php

namespace App\Basket;

class BasketProductHandler {

    private function prepareProductForCookie(array $productAddedToBasket) {
        $product = [
            'id' => $productAddedToBasket['id'],
            'quantity' => $productAddedToBasket['quantity']
        ];

        return $product;
    }

    private function changeProductQuantity(array $basket, array $product) {
        if (array_key_exists($product[key($product)], $basket['products'])) {
            $basket['products'][$product['id']]['quantity'] = $product['quantity'];
        }

        return $basket;
    }

    public function addProductToBasket(array $productAddedToBasket) {
        $basket = $this->getValueOfBasketCookie();

        $product = $this->prepareProductForCookie($productAddedToBasket);

        if (empty($basket['products'][$productAddedToBasket['id']])) {
            $basket['products'][$productAddedToBasket['id']] = $product;
        } else {
            $basket = $this->changeProductQuantity($basket, $product);
        }

        $this->setValueOfBasketCookie($basket);

        return true;
    }

}
