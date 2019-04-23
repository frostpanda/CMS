<?php

namespace App\Basket;

use App\Entity\DiscountCodes;
use App\Entity\Products;
use App\Utils\AppMessages;
use App\Utils\CookieHandler;
use App\Validator\Constraints\DiscountCodeValidators;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BasketHandler {

    private $cookie;
    private $cookieName = 'basket';
    private $discountCodeValidators;
    private $entityManager;
    private $appMessages;
    private $request;

    function __construct(ContainerInterface $containerInterface, CookieHandler $cookie, DiscountCodeValidators $discountCodeValidators, EntityManagerInterface $entityManager, AppMessages $appMessages, RequestStack $requestStack) {
        $this->discountCodeValidators = $discountCodeValidators;
        $this->entityManager = $entityManager;
        $this->appMessages = $appMessages;
        $this->request = $requestStack->getCurrentRequest();

        $this->cookie = $cookie
                ->setCookieName($this->cookieName)
                ->setCookieValue(array())
                ->setCookieExpiryDate($containerInterface->getParameter('basket_cookie_lifetime'))
                ->handleCookie()
        ;
    }

    private function setValueOfBasketCookie(array $cookieValue) {
        $this->cookie
                ->setCookieValue($cookieValue)
                ->createCookie()
        ;

        return $this;
    }

    public function getValueOfBasketCookie() {
        return $this->cookie->getCookieValue();
    }

    public function getProductsInBasket() {
        $basketCookie = $this->getValueOfBasketCookie();
        return $basketCookie['products'];
    }

    public function getDisountCodeEntity() {
        $basketCookie = $this->getValueOfBasketCookie();

        if (isset($basketCookie['discountCode'])) {
            return $this->entityManager->getRepository(DiscountCodes::class)->findOneBy(['code' => $basketCookie['discountCode']]);
        } else {
            return false;
        }
    }

    public function getTotalOrderPrice() {
        $basketCookie = $this->getValueOfBasketCookie();

        return $basketCookie['total'];
    }

    private function calculatePercentageOfSavings(float $productCurrentPrice, float $productOldPrice) {
        return round(($productOldPrice * 100) / $productCurrentPrice - 100);
    }

    private function calculateProductPrice(float $productCurrentPrice, int $productQuantity) {
        return $productCurrentPrice * $productQuantity;
    }

    private function prepareProductForTemplate(array $product, int $quantity) {
        $product['savings'] = $this->calculatePercentageOfSavings($product['price'], $product['old_price']);
        $product['quantity'] = $quantity;
        $product['total'] = $this->calculateProductPrice($product['price'], $quantity);

        return $product;
    }

    private function getDiscountCodeValue(array $basketCookie) {
        if (isset($basketCookie['discountCode'])) {
            return $basketCookie['discountCode']['discount'];
        } else {
            return 0;
        }
    }

    private function calculateOrderPriceAfterDiscount(float $orderTotalPrice, int $discount) {
        $discountMultiplier = (100 - $discount) / 100;

        return round($orderTotalPrice * $discountMultiplier, 2);
    }

    public function prepareDatasetForTemplate() {
        $basketCookie = $this->getValueOfBasketCookie();

        if (empty($basketCookie['products'])) {
            return false;
        }

        $basketDatasetForTemplate['total'] = 0;

        foreach ($basketCookie['products'] as $product) {
            $productInBasket = $this->prepareProductForTemplate($this->entityManager->getRepository(Products::class)->getProductByID($product['id']), $product['quantity']);

            $basketDatasetForTemplate['productsInBasket'][] = $productInBasket;

            $basketDatasetForTemplate['total'] += $productInBasket['total'];
        }

        $discountValue = $this->getDiscountCodeValue($basketCookie);

        if ($discountValue) {
            $orderTotalPriceWithDiscount = $this->calculateOrderPriceAfterDiscount($basketDatasetForTemplate['total'], $discountValue);
            $basketCookie['total'] = $orderTotalPriceWithDiscount;
            $basketDatasetForTemplate['orderTotalWithDiscount'] = $orderTotalPriceWithDiscount;
        } else {
            $basketCookie['total'] = $basketDatasetForTemplate['total'];
        }

        $this->setValueOfBasketCookie($basketCookie);

        return $basketDatasetForTemplate;
    }

    private function prepareProductForCookie(array $productAddedToBasket) {
        $product = [
            'id' => $productAddedToBasket['id'],
            'quantity' => $productAddedToBasket['quantity']
        ];

        return $product;
    }

    private function changeProductQuantity(array $basket, array $product) {
        $basket['products'][$product['id']]['quantity'] = $product['quantity'];

        return $basket;
    }

    public function addProduct(array $productAddedToBasket) {
        $basket = $this->getValueOfBasketCookie();

        $product = $this->prepareProductForCookie($productAddedToBasket);

        if (isset($basket['products'][$productAddedToBasket['id']])) {
            $basket = $this->changeProductQuantity($basket, $product);
        } else {
            $basket['products'][$productAddedToBasket['id']] = $product;
        }

        $this->setValueOfBasketCookie($basket);

        return true;
    }

    public function removeDiscountCode() {
        $basketCookie = $this->getValueOfBasketCookie();

        if (empty($basketCookie['discountCode'])) {
            return $this->appMessages->displayErrorMessage('Add discount code first, if you want to remove it!');
        }

        unset($basketCookie['discountCode']);

        $this->setValueOfBasketCookie($basketCookie);

        return $this->appMessages->displaySuccessMessage('Discount code was removed!');
    }

    public function removeProduct($productID) {
        $basketCookie = $this->getValueOfBasketCookie();

        $elementsInBasketCookie = count($basketCookie['products']);
        $checkIfProductInBasket = array_key_exists($productID, $basketCookie['products']);

        if ($checkIfProductInBasket && $elementsInBasketCookie > 1) {
            unset($basketCookie['products'][$productID]);
        } else if ($checkIfProductInBasket && $elementsInBasketCookie == 1) {
            unset($basketCookie['products']);
            unset($basketCookie['total']);
        } else {
            $this->appMessages->displayErrorMessage('Invalid argument!');
        }

        return $this->cookie->setCookieValue($basketCookie)->createCookie();
    }

    public function deleteBasketCookie() {
        if ($this->getValueOfBasketCookie()) {
            return $this->cookie->deleteCookie($this->cookieName);
        }
    }

    private function checkIfDiscountCodeIsAdded(string $discountCode) {
        $basketCookie = $this->getValueOfBasketCookie();

        if (isset($basketCookie['discountCode'])) {
            if ($basketCookie['discountCode']['code'] == $discountCode) {
                return $this->appMessages->displayErrorMessage('Discount code added to the order!!');
            }

            return $this->appMessages->displayErrorMessage('Discount code already added! Remove current discount code first to add another one!');
        }
    }

    private function validateDiscountCode($enteredDiscountCode) {
        $errorMesage = 'Entered discount code is invalid!';

        if ($this->discountCodeValidators->validateIfFieldsEmpty($enteredDiscountCode) === false) {
            return false;
        }

        if ($this->checkIfDiscountCodeIsAdded($enteredDiscountCode) === false) {
            return false;
        }

        if ($this->discountCodeValidators->validateDiscountCodeNameField($enteredDiscountCode, $errorMesage) === false) {
            return false;
        }

        if (count($this->entityManager->getRepository(DiscountCodes::class)->getDiscountCodeID($enteredDiscountCode)) == 0) {
            return $this->appMessages->displayErrorMessage($errorMesage);
        }

        return true;
    }

    private function verifyIfDiscountCodeExpired(\DateTime $discountCodeExpiryDate) {
        $currentDate = new \DateTime('now');

        if ($currentDate > $discountCodeExpiryDate) {
            return $this->appMessages->displayErrorMessage('Discount code expired!');
        }

        return true;
    }

    public function addDiscountCode(array $orderDiscountCode) {
        if ($this->validateDiscountCode($orderDiscountCode['discountCode']) === false) {
            return false;
        }

        $discountCode = $this->entityManager->getRepository(DiscountCodes::class)->getDiscountCode($orderDiscountCode['discountCode']);
        
        if (empty($discountCode)){
            return $this->appMessages->displayErrorMessage('Invalid discount code!');
        }
        
        if ($this->verifyIfDiscountCodeExpired($discountCode['expiry_date']) === false) {
            return false;
        }

        $basketCookie = $this->getValueOfBasketCookie();

        $basketCookie['discountCode'] = ['code' => $orderDiscountCode['discountCode'], 'discount' => $discountCode['discount']];

        if ($this->setValueOfBasketCookie($basketCookie)) {
            return $this->appMessages->displaySuccessMessage('Discount code added!');
        } else {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

}
