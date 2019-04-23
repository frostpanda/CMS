<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Psr\Container\ContainerInterface;

class OrderForm extends AbstractType {

    private $frameworkParameters;

    function __construct(ContainerInterface $containerInterface) {
        $this->frameworkParameters = $containerInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'First name:',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 3,
                            'max' => 30,
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('polish_names_pattern'),
                            'message' => 'Only letters are allowed!'
                                ])
                    ]
                ))
                ->add('surname', TextType::class, array(
                    'label' => 'Last name:',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 4,
                            'max' => 30
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('polish_names_pattern'),
                            'message' => 'Only letters are allowed!'
                                ])
                    ]
                ))
                ->add('company', TextType::class, array(
                    'label' => 'Company name:',
                    'required' => false,
                    'constraints' => [
                        new Length([
                            'min' => 4,
                            'max' => 50
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('polish_names_pattern'),
                            'message' => 'Only letters are allowed!'
                                ])
                    ]
                ))
                ->add('city', TextType::class, array(
                    'label' => 'City name:',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 6,
                            'max' => 50
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('polish_names_pattern'),
                            'message' => 'Only letters are allowed!'
                                ])
                    ]
                ))
                ->add('zip', TextType::class, array(
                    'label' => 'Zip code:',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 6,
                            'max' => 10
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('poland_zip_code_pattern'),
                            'message' => 'Invalid zip code! Zip code format: XX-XXX'
                                ])
                    ]
                ))
                ->add('street', TextType::class, array(
                    'label' => 'Street name:',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 4,
                            'max' => 30
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('street_name_pattern'),
                            'message' => 'Only letters, spaces and numbers are allowed!'
                                ])
                    ]
                ))
                ->add('house', TextType::class, array(
                    'label' => 'House number:',
                    'required' => false,
                    'constraints' => [
                        new Length([
                            'min' => 1,
                            'max' => 5
                                ]),
                        new Regex([
                            'pattern' => $this->frameworkParameters->getParameter('house_number_pattern'),
                            'message' => 'Only letters and numbers are allowed!'
                                ])
                    ]
                ))
                ->add('flat_number', IntegerType::class, array(
                    'label' => 'Flat number:',
                    'attr' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'E-mail:',
                    'constraints' => [
                        new NotBlank(),
                        new Email(),
                    ]
                ))
                ->add('phone', NumberType::class, array(
                    'label' => 'Phone number:',
                    'constraints' => [
                        new NotBlank(),
                        
                    ]
                ))
                ->add('shipping_method', ChoiceType::class, array(
                    'label' => 'Shipping method:',
                    'choices' => [
                        'Standard shipment' => 'standard',
                        'DHL/FedEx shipment' => 'express'
                    ]
                ))
                ->add('payment_method', ChoiceType::class, array(
                    'label' => 'Payment method:',
                    'choices' => [
                        'Credit card' => 'credit_card',
                        'Paypal' => 'paypal'
                    ]
                ))
                ->add('submitOrder', SubmitType::class, array(
                    'label' => $options['submitLabel']
                ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'submitLabel' => 'Place order'
        ));
    }
}
