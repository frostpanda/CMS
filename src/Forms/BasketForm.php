<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BasketForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('quantity', ChoiceType::class, array(
                    'choices' => [
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5
                    ]
                ))
                ->add('discountCode', TextType::class, array(
                ))
                ->add('addDiscountCode', SubmitType::class, array(
                    'label' => 'Add discount code',
                ))
                ->add('modifyProductQuantity', SubmitType::class, array(
                    'label' => 'Change quantity',
                ))
                ->add('orderProducts', SubmitType::class, array(
                    'label' => 'Order products',
                ))
        ;
    }

}
