<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountCodeForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('code', TextType::class, array(
                    'label' => 'Discount code:'
                ))
                ->add('discount', IntegerType::class, array(
                    'label' => 'Discount value:'
                ))
                ->add('expiry_date', DateType::class, array(
                    'label' => 'Expiry date:',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy'
                ))
                ->add('save', SubmitType::class, array(
                    'label' => $options['submitLabel']
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'submitLabel' => 'Add discout code'
        ));
    }

}
