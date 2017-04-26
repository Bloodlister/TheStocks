<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/processing')
            ->add('fullName', null, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('phoneNumber', null, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('country', null, [
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
                    'class' => 'col-md-offset-3 col-md-6 col-xs-offset-3 col-xs-6 btn btn-lg btn-success'
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

    public function getBlockPrefix()
    {
        return 'app_bundle_purchase_form';
    }
}
