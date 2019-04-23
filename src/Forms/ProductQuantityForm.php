<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProductQuantityForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('quantity', ChoiceType::class, array(
                    'label' => 'Product quantity',
                    'choice_translation_domain' => false,
                    'choices' => [
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                        '6' => 6, 
                        '7' => 7,
                        '8' => 8,
                        '9' => 9,
                    ]
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'Add product',
                ))
        ;
    }

//    public function configureOptions(OptionsResolver $resolver) {
//        $resolver->setDefault();
//    }

}
