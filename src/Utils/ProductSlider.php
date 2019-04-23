<?php

namespace App\Utils;

use App\Entity\Products;
use App\Entity\Sliders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductSlider {

    private $entityManager;
    private $appMessages;
    private $frameworkParameters;

    function __construct(ContainerInterface $containerInterface, EntityManagerInterface $entityManagerInterface, AppMessages $appMessages) {
        $this->frameworkParameters = $containerInterface;
        $this->entityManager = $entityManagerInterface;
        $this->appMessages = $appMessages;
    }

    private function getSlidersRepository() {
        return $this->entityManager->getRepository(Sliders::class);
    }

    private function getCurrentDateTime() {
        return new \DateTime('now');
    }

    private function checkImagePositionUp($sliderImage) {
        if ($sliderImage->getPosition() == 1) {
            return $this->appMessages->displayWarningMessage('Image position can not be higher!');
        }

        return true;
    }

    private function checkImagePositionDown($productEntity, $sliderImage) {
        if ($sliderImage->getPosition() == $this->getSlidersRepository()->getNumberOfImagesInSlider($productEntity)) {
            return $this->appMessages->displayWarningMessage('Image position can not be lower!');
        }

        return true;
    }

    private function checkIfImageWithSpecificPositionExist($productSliderImage) {
        if (empty($productSliderImage)) {
            return $this->appMessages->displayWarningMessage('Image with lower position does not exist!');
        }

        return true;
    }

    private function changeImagesPosition($sliderImageUp, $sliderImageDown) {
        try {
            $sliderImageUp->setModified($this->getCurrentDateTime());
            $sliderImageDown->setModified($this->getCurrentDateTime());

            $this->entityManager->persist($sliderImageUp);
            $this->entityManager->persist($sliderImageDown);

            $this->entityManager->flush();
        } catch (Exception $ex) {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

    public function imagePositionInSliderUp(Products $productEntity, $sliderImageID) {
        $productSliderImageUp = $this->getSlidersRepository()->findOneBy(['id' => $sliderImageID, 'product' => $productEntity, 'deleted' => null]);

        if ($this->checkImagePositionUp($productSliderImageUp) === false) {
            return false;
        }

        $productSliderImageDown = $this->getSlidersRepository()->findOneBy(['product' => $productEntity, 'position' => $productSliderImageUp->getPosition() - 1, 'deleted' => null]);

        if ($this->checkIfImageWithSpecificPositionExist($productSliderImageDown) === false) {
            return false;
        }

        $productSliderImageDown->setPosition($productSliderImageUp->getPosition());
        $productSliderImageUp->setPosition($productSliderImageUp->getPosition() - 1);

        return $this->changeImagesPosition($productSliderImageUp, $productSliderImageDown);
    }

    public function imagePositionInSliderDown(Products $productEntity, $sliderImageID) {
        $productSliderImageDown = $this->getSlidersRepository()->findOneBy(['id' => $sliderImageID, 'product' => $productEntity, 'deleted' => null]);

        if ($this->checkImagePositionDown($productEntity, $productSliderImageDown) === false) {
            return false;
        }

        $productSliderImageUp = $this->getSlidersRepository()->findOneBy(['product' => $productEntity, 'position' => $productSliderImageDown->getPosition() + 1, 'deleted' => null]);

        if ($this->checkIfImageWithSpecificPositionExist($productSliderImageUp) === false) {
            return false;
        }

        $productSliderImageUp->setPosition($productSliderImageDown->getPosition());
        $productSliderImageDown->setPosition($productSliderImageDown->getPosition() + 1);

        return $this->changeImagesPosition($productSliderImageUp, $productSliderImageDown);
    }

    private function repositionProductSliderImage($productEntity, $sliderImagePosition) {
        try {
            $imageInSlider = $this->getSlidersRepository()->findOneBy(['product' => $productEntity->getId(), 'position' => $sliderImagePosition]);
            $imageInSlider->setPosition($sliderImagePosition - 1);
            $imageInSlider->setModified($this->getCurrentDateTime());

            $this->entityManager->persist($imageInSlider);
        } catch (Exception $ex) {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

    private function deleteProductSliderImage($sliderProductImage) {
        try {
            $sliderProductImage->setPosition(0);
            $sliderProductImage->setDeleted($this->getCurrentDateTime());
            $this->entityManager->persist($sliderProductImage);
        } catch (Exception $ex) {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

    public function deleteSliderImage(Products $productEntity, Sliders $sliderProductImage) {
        try {
            $numberOfImagesInSlider = $this->getSlidersRepository()->getNumberOfImagesInSlider($productEntity);

            for ($position = $numberOfImagesInSlider; $position > $sliderProductImage->getPosition(); $position--) {
                $this->repositionProductSliderImage($productEntity, $position);
            }
            $this->deleteProductSliderImage($sliderProductImage);

            $this->entityManager->flush();
            return $this->appMessages->displaySuccessMessage('Image from slider was deleted!');
        } catch (Exception $ex) {
            return $this->appMessages->displayErrorMessage('Something goes wrong!');
        }
    }

}
