<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Sliders;
use App\Utils\ProductSlider;
use App\Utils\UrlHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductSliderController extends AbstractController {

    private $urlHandler;

    function __construct(UrlHandler $urlHandler) {
        $this->urlHandler = $urlHandler;
    }

    private function getSlidersRepository() {
        return $this->getDoctrine()->getRepository(Sliders::class);
    }

    private function getProductEntity($productURL) {
        return $this->getDoctrine()->getRepository(Products::class)->findOneBy(['url' => $productURL, 'deleted' => null]);
    }

    public function generateProductSliderPage($productURL) {
        $product = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }

        $productSliderImages = $this->getSlidersRepository()->findBy(['product' => $product, 'deleted' => null], ['position' => 'ASC']);

        return $this->render('/cms/panel/products/slider.html.twig', array(
                    'editProduct' => 'active',
                    'dropdownProduct' => 'style=display:block;',
                    'pageTitle' => 'Product slider',
                    'previousPageUrl' => 'product_list',
                    'sliderImages' => $productSliderImages,
        ));
    }

    public function sliderChangeImagePositionUp(ProductSlider $productSlider, $productURL, $sliderImageID) {
        $product = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }

        $productSliderImage = $this->getSlidersRepository()->findOneBy(['id' => $sliderImageID, 'product' => $product, 'deleted' => null]);

        if ($this->urlHandler->verifyUrlParameter($productSliderImage)) {
            return $this->redirectToRoute('product_list');
        }

        $productSlider->imagePositionInSliderUp($product, $productSliderImage);

        return $this->redirectToRoute('product_slider', array('productURL' => $productURL));
    }

    public function sliderChangeImagePositionDown(ProductSlider $productSlider, $productURL, $sliderImageID) {
        $product = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }

        $productSliderImage = $this->getSlidersRepository()->findOneBy(['id' => $sliderImageID, 'product' => $product, 'deleted' => null]);

        if ($this->urlHandler->verifyUrlParameter($productSliderImage)) {
            return $this->redirectToRoute('product_list');
        }

        $productSlider->imagePositionInSliderDown($product, $productSliderImage);

        return $this->redirectToRoute('product_slider', array('productURL' => $productURL));
    }

    public function deleteImageFromSlider(ProductSlider $productSlider, $productURL, $sliderImageID) {
        $product = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }
        
        $sliderProductImage = $this->getSlidersRepository()->findOneBy(['id' => $sliderImageID, 'product' => $product->getId(), 'deleted' => null]);

        if ($this->urlHandler->verifyUrlParameter($sliderProductImage)) {
            return $this->redirectToRoute('product_list');
        }
        
        if ($productSlider->deleteSliderImage($product, $sliderProductImage) == false) {
            die;
            return $this->redirectToRoute('product_list');
        }

        return $this->redirectToRoute('product_slider', array('productURL' => $productURL));
    }

}
