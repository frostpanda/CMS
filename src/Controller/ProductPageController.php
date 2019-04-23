<?php

namespace App\Controller;

use App\Basket\BasketHandler;
use App\Entity\Images;
use App\Entity\Products;
use App\Entity\Sliders;
use App\Entity\Subpages;
use App\Forms\ProductQuantityForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductPageController extends AbstractController {

    public function generateProductListPage() {
        $productList = $this->getDoctrine()->getRepository(Products::class)->getProductList();

        $subpage = $this->getDoctrine()->getRepository(Subpages::class)->findOneBy(['url' => 'contact-us']);

        return $this->render('product_page/list.html.twig', array(
                    'productList' => $productList,
                    'subpage' => $subpage
        ));
    }

    public function generateProductPage(BasketHandler $basketHandler, Request $request, $productURL) {
        $product = $this->getDoctrine()->getRepository(Products::class)->findOneBy(['url' => $productURL]);

        $productGallery = $this->getDoctrine()->getRepository(Images::class)->findBy(['product' => $product, 'deleted' => null], array(), 3);
        $productSlider = $this->getDoctrine()->getRepository(Sliders::class)->findBy(['product' => $product, 'deleted' => null], ['position' => 'asc'], 3);

        $addProductForm = $this->createForm(ProductQuantityForm::class);

        $addProductForm->handleRequest($request);

        if ($addProductForm->isSubmitted() && $addProductForm->isValid()) {
            $addedProductToBasket['id'] = $product->getId();
            $addedProductToBasket['quantity'] = $addProductForm->get('quantity')->getData();

            $basketHandler->addProduct($addedProductToBasket);

            return $this->redirectToRoute('basket');
        }


        return $this->render('product_page/page.html.twig', array(
                    'form' => $addProductForm->createView(),
                    'product' => $product,
                    'productGallery' => $productGallery,
                    'productSlider' => $productSlider
        ));
    }

}
