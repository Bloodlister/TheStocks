<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setRequired(false)
            ->add('fullName', null , [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Jane Doe'
                ]
            ])
            ->add('email', null , [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('bio', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('age', null , [
                'attr' => [
                    'min' => 13,
                    'max' => 100,
                    'placeholder' => "Between 13 and 100"
                ]
            ])
            ->add('phoneNumber', null, [
                'attr' => [
                    'maxlength' => '20',
                    'class' => 'form-control'
                ]
            ])
            ->add('country',null , [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address', null, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('postCode', null, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-primary col-xs-12 col-md-offset-3 col-md-6'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_edit_user';
    }
}
