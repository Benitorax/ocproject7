<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Address.',
                ],
            ])
            ->add('city', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'City.',
                ],
            ])
            ->add('zipCode', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'ZIP code.',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
