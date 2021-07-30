<?php

namespace App\Form;

use App\DTO\Address\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => "The address must be at least {{ limit }} characters long",
                        'maxMessage' => "The address cannot be longer than {{ limit }} characters",
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'City.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 4,
                        'max' => 100,
                        'minMessage' => "The city must be at least {{ limit }} characters long",
                        'maxMessage' => "The city cannot be longer than {{ limit }} characters",
                    ]),
                ],
            ])
            ->add('zipCode', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'ZIP code.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'minMessage' => "The ZIP code must be at least {{ limit }} characters long",
                        'maxMessage' => "The ZIP code cannot be longer than {{ limit }} characters",
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'csrf_protection' => false,
        ]);
    }
}
