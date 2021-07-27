<?php

namespace App\Form;

use App\Entity\Customer;
use App\Form\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gender', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Gender: "Mr.", "Ms." or "Miss".',
                ],
            ])
            ->add('firstName', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'First name.',
                ],
            ])
            ->add('lastName', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Last name.',
                ],
            ])
            ->add('email', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Email address.',
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Phone number.',
                ],
            ])
            ->add('address', AddressType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
