<?php

namespace App\Utils;

use App\Entity\Images;
use App\Entity\Products;
use App\Entity\Sliders;
use App\Utils\AppMessages;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class ProductHandler {

    private $entityManager;
    private $appMessages;
    private $frameworkParameters;

    function __construct(ContainerInterface $containerInterface, EntityManagerInterface $entityManagerInterface, AppMessages $appMessages) {
        $this->frameworkParameters = $containerInterface;
        $this->entityManager = $entityManagerInterface;
        $this->appMessages = $appMessages;
    }

    private function getCurrentDateTime() {
        return new \DateTime('now');
    }

    private function prepareImageEntity($productEntity, $productImageFile) {
        $productImage = new Images();
        $productImage->uploadFile($this->frameworkParameters->getParameter('upload_directory_products'), $productImageFile);

        $productEntity->addProductImage($productImage);

        return $productImage;
    }

    private function persistProductImages($productEntity) {
        try {
            foreach ($productEntity->getImages() as $productImageFile) {
                $productImage = $this->prepareImageEntity($productEntity, $productImageFile);
                $this->entityManager->persist($productImage);
            }
        } catch (Exception $ex) {
            $this->appMessages->displayWarningMessage('Something goes wrong!');
        }
    }

    private function prepareSliderEntity($productEntity, $productSliderImageFile) {
        $productSliderImage = new Sliders();
        $productSliderImage->uploadFile($this->frameworkParameters->getParameter('upload_directory_products'), $productSliderImageFile);

        $productEntity->addProductSlider($productSliderImage);

        return $productSliderImage;
    }

    private function persistProductSlider($productEntity) {
        try {
            $positionCounter = $this->entityManager->getRepository(Sliders::class)->getNumberOfImagesInSlider($productEntity) + 1;

            foreach ($productEntity->getSliderImages() as $productSliderImage) {
                $productSliderImage = $this->prepareSliderEntity($productEntity, $productSliderImage);
                $productSliderImage->setPosition($positionCounter++);
                $this->entityManager->persist($productSliderImage);
            }
        } catch (Exception $ex) {
            $this->appMessages->displayWarningMessage('Something goes wrong!');
        }
    }

    private function pushDataToDatabase($productEntity) {
        try {
            $this->entityManager->persist($productEntity);

            if (!empty($productEntity->getImages())) {
                $this->persistProductImages($productEntity);
            }

            if (!empty($productEntity->getSliderImages())) {
                $this->persistProductSlider($productEntity);
            }

            $this->entityManager->flush();

            return true;
        } catch (Exception $ex) {
            return $this->addFlash('danger', 'Something goes wrong!');
        }
    }

    public function insertProductToDatabase(Products $productEntity) {
        $productEntity->setCreated($this->getCurrentDateTime());

        $this->pushDataToDatabase($productEntity);
        return $this->appMessages->displaySuccessMessage('Product ' . $productEntity->getName() . ' added!');
    }

    public function updateProductRecord(Products $productEntity) {
        $productEntity->setModified($this->getCurrentDateTime());

        $this->pushDataToDatabase($productEntity);
        return $this->appMessages->displaySuccessMessage('Product ' . $productEntity->getName() . ' updated!');
    }

    public function deleteProductRecord(Products $productEntity) {
        $productEntity->setDeleted($this->getCurrentDateTime());

        try {
            foreach ($productEntity->getProductImages() as $productImage) {
                $productImage->setDeleted($this->getCurrentDateTime());
                $this->entityManager->persist($productImage);
            }

            foreach ($productEntity->getProductSlider() as $productSliderImage) {
                $productSliderImage->setDeleted($this->getCurrentDateTime());
                $this->entityManager->persist($productSliderImage);
            }

            $this->entityManager->flush();
            return $this->appMessages->displaySuccessMessage('Product deleted!');
        } catch (Exception $ex) {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

}
