<?php

namespace App\Form;

use App\DTO\Customer\CreateCustomer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreateCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gender', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Gender: "Mr.", "Ms." or "Miss".',
                ],
                'constraints' => [
                    new Choice(['Mr.', 'Ms.', 'Miss']),
                ],
            ])
            ->add('firstName', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'First name.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => "Your first name must be at least {{ limit }} characters long",
                        'maxMessage' => "Your first name cannot be longer than {{ limit }} characters",
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Last name.',
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => "Your last name must be at least {{ limit }} characters long",
                        'maxMessage' => "Your last name cannot be longer than {{ limit }} characters",
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Email address.',
                ],
                'constraints' => [
                    'min' => 10,
                    'max' => 255,
                    'minMessage' => "Your email must be at least {{ limit }} characters long",
                    'maxMessage' => "Your email cannot be longer than {{ limit }} characters",
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Phone number.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 20,
                        'minMessage' => "Your phone number must be at least {{ limit }} characters long",
                        'maxMessage' => "Your phone number cannot be longer than {{ limit }} characters",
                    ]),
                ],
            ])
            ->add('address', AddressType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateCustomer::class,
        ]);
    }
}