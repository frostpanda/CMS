<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Products;
use App\Utils\DatabaseHandler;
use App\Utils\FormHandler;
use App\Utils\ProductHandler;
use App\Utils\UrlHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductGalleryController extends AbstractController {

    private $formHandler;
    private $productHandler;
    private $urlHandler;

    function __construct(FormHandler $formHandler, ProductHandler $productHandler, UrlHandler $urlHandler) {
        $this->formHandler = $formHandler;
        $this->productHandler = $productHandler;
        $this->urlHandler = $urlHandler;
    }

    private function getImagesRepository() {
        return $this->getDoctrine()->getRepository(Images::class);
    }

    private function getProductEntity($productURL) {
        return $this->getDoctrine()->getRepository(Products::class)->findOneBy(['url' => $productURL, 'deleted' => null]);
    }

    public function generateProductGalleryPage($productURL) {
        $product = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }

        $productImages = $this->getImagesRepository()->getProductImagesByProductID($product);

        return $this->render('/cms/panel/products/gallery.html.twig', array(
                    'editProduct' => 'active',
                    'dropdownProduct' => 'style=display:block;',
                    'pageTitle' => 'Product gallery',
                    'previousPageUrl' => 'product_list',
                    'product' => $product,
                    'productImages' => $productImages
        ));
    }

    public function deleteProductImage(DatabaseHandler $databaseHandler, UrlHandler $urlHandler, $productURL, $productImageID) {
        $product = $this->getProductEntity($productURL);

        if ($urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }

        $productImage = $this->getImagesRepository()->findOneBy(['id' => $productImageID, 'product' => $product, 'deleted' => null]);

        if ($databaseHandler->deleteRecord($productImage)) {
            return $this->redirectToRoute('product_gallery', array('productURL' => $productURL));
        } else {
            return $this->redirectToRoute('product_list');
        }
    }

}
