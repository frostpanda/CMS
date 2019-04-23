<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariableForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('tag', TextType::class, array(
                    'label' => 'Variable name',
                ))
                ->add('val', TextType::class, array(
                    'label' => 'Variable value:',
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => $options['submitLabel']
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'submitLabel' => 'Add new variable',
        ));
    }

}
