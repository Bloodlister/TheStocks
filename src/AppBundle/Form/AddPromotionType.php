<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discount')
            ->add('startDate', null, [
                'data' => new \DateTime('-1 days')
            ])
            ->add('endDate', null, [
                'data' => new \DateTime('+10 days')
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ItemPromotion'
        ));

    }

    public function getBlockPrefix()
    {
        return 'app_bundleadd_promotion_type';
    }
}
