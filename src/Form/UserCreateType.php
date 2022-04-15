<?php

namespace App\Form;

use App\Entity\User;
use App\Form\EventListener\UsenameValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class UserCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'disabled' => $options['is_edit'],                
                'attr' => ['readonly' => $options['is_edit'],],                
            ])
            ->add('password', PasswordType::class, [
                'mapped' => !$options['is_edit'],
                'required' => !$options['is_edit'],
            ])
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'mapped' => false,
                'constraints' => array(new NotBlank(),),
                'data' => $options['selected_role'],
            ])
            ->add('save', SubmitType::class , [
            ]);
        ;
        $builder->get('username')->addEventSubscriber(new UsenameValidator());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'selected_role' => 'User',
        ]);
    }
}
