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
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class CreateCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gender', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Gender: Mr., Ms. or Miss.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Choice(
                        ['Mr.', 'Ms.', 'Miss'],
                        null,
                        null,
                        null,
                        null,
                        null,
                        'Gender should be "Mr.", "Ms." or "Miss"'
                    ),
                ],
            ])
            ->add('firstName', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'First name.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex('#^[A-Za-z]+$#', "Only letters are valid characters"),
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
                    new NotBlank(),
                    new Regex('#^[A-Za-z]+$#', "Only letters are valid characters"),
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
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => "Your email must be at least {{ limit }} characters long",
                        'maxMessage' => "Your email cannot be longer than {{ limit }} characters",
                    ]),
                    new Email(),
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'documentation' => [
                    'type' => 'string',
                    'description' => 'Phone number.',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Regex('#^[0-9]+$#', "Only numbers are valid characters"),
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
            'csrf_protection' => false,
        ]);
    }
}
