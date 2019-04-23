<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PasswordForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('currentPassword', PasswordType::class, array(
                    'label' => 'Password:',
//                    'required' => false,
                ))
                ->add('newPassword', PasswordType::class, array(
                    'label' => 'New password',
//                    'required' => false,
                ))
                ->add('confirmPassword', PasswordType::class, array(
                    'label' => 'Confirm password:',
//                    'required' => false,
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Change password',
                ))
        ;
    }

}
