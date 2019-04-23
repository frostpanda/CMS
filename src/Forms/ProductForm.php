<?php

namespace App\Forms;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ProductForm extends AbstractType {

    private $entityManager;

    function __construct(ContainerInterface $containerInterface, EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->frameworkParameters = $containerInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $categories = $this->entityManager->getRepository(Categories::class)->findBy(array('deleted' => null));

        $fieldBlankMessage = 'This field should not be blank';
        $choiceTypeBlankMessage = 'You need to choose option!';

        $builder
                ->add('category', EntityType::class, array(
                    'placeholder' => 'Choose an option',
                    'label' => 'Product category',
//                    'required' => false,
                    'class' => Categories::class,
                    'choices' => $categories,
                    'choice_label' => 'name',
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $choiceTypeBlankMessage,
                                ]),
                    ]
                ))
                ->add('name', TextType::class, array(
                    'label' => 'Product name:',
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $fieldBlankMessage
                                ]),
                        new Length([
                            'groups' => ['editProduct'],
                            'min' => 3,
                            'max' => 50,
                                ]),
                        new Regex([
                            'groups' => ['editProduct'],
                            'pattern' => $this->frameworkParameters->getParameter('form_name_pattern'),
                                ])
                    ]
                ))
                ->add('url', TextType::class, array(
                    'label' => 'Product URL:',
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $fieldBlankMessage
                                ]),
                        new Length([
                            'groups' => ['editProduct'],
                            'min' => 3,
                            'max' => 50,
                                ]),
                        new Regex([
                            'groups' => ['editProduct'],
                            'pattern' => $this->frameworkParameters->getParameter('form_url_pattern'),
                            'message' => 'Only letters and dashes are allowed!'
                                ])
                    ]
                ))
                ->add('price', NumberType::class, array(
                    'scale' => 2,
                    'label' => 'Product price:',
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $fieldBlankMessage
                                ]),
                        new GreaterThan([
                            'groups' => ['editProduct'],
                            'value' => 0
                                ]),
                        new LessThan([
                            'groups' => ['editProduct'],
                            'value' => 10000
                                ])
                    ]
                ))
                ->add('old_price', NumberType::class, array(
                    'scale' => 2,
                    'label' => 'Product old price:',
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $fieldBlankMessage
                                ]),
                        new GreaterThan([
                            'groups' => ['editProduct'],
                            'value' => 0
                                ]),
                        new LessThan([
                            'groups' => ['editProduct'],
                            'value' => 10000
                                ])
                    ]
                ))
                ->add('rating', NumberType::class, array(
                    'label' => 'Product rating:',
                    'scale' => 2,
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $fieldBlankMessage
                                ]),
                        new GreaterThanOrEqual([
                            'groups' => ['editProduct'],
                            'value' => 1
                                ]),
                        new LessThanOrEqual([
                            'groups' => ['editProduct'],
                            'value' => 6
                                ])
                    ]
                ))
                ->add('votes', IntegerType::class, array(
                    'label' => 'Votes:',
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $fieldBlankMessage
                                ]),
                        new GreaterThanOrEqual([
                            'groups' => ['editProduct'],
                            'value' => 0
                                ]),
                        new LessThanOrEqual([
                            'groups' => ['editProduct'],
                            'value' => 100000
                                ])
                    ]
                ))
                ->add('promoted', ChoiceType::class, array('label' => 'Product promoted:',
                    'placeholder' => 'Choose an option',
//                    'required' => false,
                    'choices' => [
                        'Yes' => 1,
                        'No' => 0
                    ],
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $choiceTypeBlankMessage
                                ]),
                    ]
                ))
                ->add('on_stock', ChoiceType::class, array(
                    'label' => 'Product on stock:',
                    'placeholder' => 'Choose an option',
//                    'required' => false,
                    'choices' => [
                        'Yes' => 1,
                        'No' => 0
                    ],
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                            'message' => $choiceTypeBlankMessage
                                ]),
                    ]
                ))
                ->add('description', TextareaType::class, array(
                    'label' => 'Product description:',
//                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'groups' => ['editProduct'],
                                ]),
                        new Length([
                            'groups' => ['editProduct'],
                            'min' => 10,
                            'max' => 100000,
                                ]),
                    ]
                ))
                ->add('images', FileType::class, array(
                    'label' => 'Add product images:',
                    'required' => false,
                    'multiple' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => $fieldBlankMessage
                                ]),
                        new Count([
                            'groups' => ['editProduct'],
                            'max' => 5,
                            'maxMessage' => 'Only {{ limit }} images are allowed!',
                                ]),
                        new All([
                            new Image([
                                'groups' => ['editProduct'],
                                'maxSize' => $this->frameworkParameters->getParameter('product_image_max_size'),
                                'uploadIniSizeErrorMessage' => 'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.',
                                'mimeTypes' => ['image/png', 'image/jpeg'],
                                'mimeTypesMessage' => 'Wrong extension! PNG files allowed!',
                                'minHeight' => $this->frameworkParameters->getParameter('product_image_min_heigth'),
                                'minWidth' => $this->frameworkParameters->getParameter('product_image_min_width'),
                                    ]),
                                ])
                    ]
                ))
                ->add('sliderImages', FileType::class, array(
                    'label' => 'Add product images for slider:',
                    'required' => false,
                    'multiple' => true,
                    'constraints' => [
                        new Count([
                            'groups' => ['editProduct'],
                            'max' => 5,
                            'maxMessage' => 'Only {{ limit }} images are allowed!',
                                ]),
                        new All([
                            new Image([
                                'groups' => ['editProduct'],
                                'maxSize' => $this->frameworkParameters->getParameter('product_image_max_size'),
                                'uploadIniSizeErrorMessage' => 'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.',
                                'mimeTypes' => ['image/png', 'image/jpeg'],
                                'mimeTypesMessage' => 'Wrong extension! PNG files allowed!',
                                'minHeight' => $this->frameworkParameters->getParameter('product_image_min_heigth'),
                                'minWidth' => $this->frameworkParameters->getParameter('product_image_min_width'),
                                    ]),
                                ])
                    ]
                ))                
                ->add('save', SubmitType::class, array(
                    'label' => $options['submitLabel']
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'validation_groups' => ['Default', 'editProduct'],
            'submitLabel' => 'Add product',
        ));
    }

}
