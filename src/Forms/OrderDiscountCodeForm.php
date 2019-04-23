<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderDiscountCodeForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('discountCode', TextType::class, array(
                    'label' => 'Discount code:',
                    'required' => false
                ))
                ->add('addDiscountCode', SubmitType::class, array(
                    'label' => 'Add discount code'
                ))
                ->add('removeDiscountCode', SubmitType::class, array(
                    'label' => 'Remove discount code'
                )) 
                ->add('placeOrder', SubmitType::class, array(
                    'label' => 'Place order'
                ))
        ;
    }

}
