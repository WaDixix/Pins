<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => true]])
            ->add('description', null, ['attr' => ['rows' => 10, 'cols' => 50]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
