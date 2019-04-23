<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Forms\CategoryForm;
use App\Utils\DatabaseHandler;
use App\Utils\UrlHandler;
use App\Validator\CategoryValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController {

    public function generateCategoryListPage(): Response {
        $categoryList = $this->getDoctrine()->getRepository(Categories::class)->getCategoryTable();

        return $this->render('cms/panel/categories/list.html.twig', array(
                    'pageTitle' => 'Category list',
                    'categoryInformation' => $categoryList,
        ));
    }

    public function generateNewCategoryPage(CategoryValidation $categoryValidation, DatabaseHandler $dbHander): Response {
        $newCategory = new Categories();

        $newCategoryForm = $this->createForm(CategoryForm::class, $newCategory);

        $validationResult = $categoryValidation->validateForm($newCategoryForm);

        if ($validationResult) {
            if ($dbHander->insertNewRecord($newCategory)) {
                return $this->redirectToRoute('category_list');
            }
        }

        return $this->render('/cms/panel/categories/form.html.twig', array(
                    'pageTitle' => 'New category',
                    'previousPageUrl' => 'category_list',
                    'form' => $newCategoryForm->createView(),
        ));
    }

    public function generateEditCategoryPage(CategoryValidation $categoryValidator, DatabaseHandler $dbHander, UrlHandler $urlHandler, $categoryURL) {
        $categoryEntity = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(['url' => $categoryURL, 'deleted' => null]);

        if ($urlHandler->verifyUrlParameter($categoryEntity)) {
            return $this->redirectToRoute('category_list');
        }

        $editDiscountCodeForm = $this->createForm(CategoryForm::class, $categoryEntity, ['submitLabel' => 'Modify category']);

        if ($categoryValidator->validateForm($editDiscountCodeForm, $categoryEntity)) {
            if ($dbHander->modifyRecord($categoryEntity)) {
                return $this->redirectToRoute('category_list');
            }
        }

        return $this->render('cms/panel/categories/form.html.twig', array(
                    'editCategory' => 'active',
                    'dropdownCategory' => 'style=display:block;',
                    'pageTitle' => 'Modify category',
                    'previousPageUrl' => 'category_list',
                    'form' => $editDiscountCodeForm->createView(),
        ));
    }

    public function deleteCategory(DatabaseHandler $databaseHandler, $categoryID): Response {
        if ($this->getDoctrine()->getRepository(Categories::class)->checkIfCategoryHasProducts($categoryID)) {
            $this->addFlash('danger', 'Category cannot be deleted! Delete products first!');
        } else {
            $databaseHandler->deleteRecord($this->getDoctrine()->getRepository(Categories::class)->findOneBy(['id' => $categoryID, 'deleted' => null]));
        }

        return $this->redirectToRoute('category_list');
    }

}
