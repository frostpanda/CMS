<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Products;
use App\Entity\Sliders;
use App\Forms\ProductForm;
use App\Utils\FormHandler;
use App\Utils\ProductHandler;
use App\Utils\UrlHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController {

    private $formHandler;
    private $productHandler;
    private $urlHandler;

    function __construct(FormHandler $formHandler, ProductHandler $productHandler, UrlHandler $urlHandler) {
        $this->formHandler = $formHandler;
        $this->productHandler = $productHandler;
        $this->urlHandler = $urlHandler;
    }

    private function getProductEntity($productURL) {
        return $this->getDoctrine()->getRepository(Products::class)->findOneBy(['url' => $productURL, 'deleted' => null]);
    }

    public function generateProductListPage() {
        $productList = $this->getDoctrine()->getRepository(Products::class)->findBy(['deleted' => null]);

        return $this->render('/cms/panel/products/list.html.twig', array(
                    'pageTitle' => 'Product list',
                    'productList' => $productList
        ));
    }

    public function generateNewProductPage() {
        $newProduct = new Products();

        $newProductForm = $this->createForm(ProductForm::class, $newProduct);

        if ($this->formHandler->symfonyValidation($newProductForm)) {
            $this->productHandler->insertProductToDatabase($newProduct);

            return $this->redirectToRoute('product_list');
        }

        return $this->render('cms/panel/products/form.html.twig', array(
                    'pageTitle' => 'New product',
                    'previusPageUrl' => 'product_list',
                    'form' => $newProductForm->createView(),
        ));
    }

    public function generateEditProductPage(Request $request, $productURL) {
        $product = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($product)) {
            return $this->redirectToRoute('product_list');
        }
        $request->attributes->add(array('currentProductUrl' => $product->getUrl()));

        $editProductForm = $this->createForm(ProductForm::class, $product, array('validation_groups' => ['editProduct'], 'submitLabel' => 'Update product'));

        if ($this->formHandler->symfonyValidation($editProductForm)) {
            $this->productHandler->updateProductRecord($product);

            return $this->redirectToRoute('product_list');
        }

        $productImages = $this->getDoctrine()->getRepository(Images::class)->getProductImagesByProductID($product);
        $productSlider = $this->getDoctrine()->getRepository(Sliders::class)->getProductSliderByProductID($product);

        return $this->render('cms/panel/products/edit.html.twig', array(
                    'editProduct' => 'active',
                    'dropdownProduct' => 'style=display:block;',
                    'pageTitle' => 'Edit product',
                    'previousPageUrl' => 'product_list',
                    'form' => $editProductForm->createView(),
                    'productImages' => $productImages,
                    'sliderImages' => $productSlider
        ));
    }

    public function deleteProduct($productURL) {
        $productToDelete = $this->getProductEntity($productURL);

        if ($this->urlHandler->verifyUrlParameter($productToDelete)) {
            return $this->redirectToRoute('product_list');
        }

        $this->productHandler->deleteProductRecord($productToDelete);

        return $this->redirectToRoute('product_list');
    }

}
