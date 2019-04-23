<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubpageForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, array(
                    'label' => "Subpage name:",
                    'required' => true
                ))
                ->add('url', TextType::class, array(
                    'label' => "Subpage URL:",
                    'required' => true
                ))
                ->add('description', TextareaType::class, array(
                    'label' => "Subpage code:",
                    'required' => true))
                ->add('save', SubmitType::class, array(
                    'label' => $options['submitLabel']
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'submitLabel' => 'Add new subpage'
        ));
    }

}
