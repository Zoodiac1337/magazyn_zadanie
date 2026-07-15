<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Warehouse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->add('warehouses', EntityType::class, [
                'class' => Warehouse::class,
                'choice_label' => 'name',
                'multiple' => true, // Allow multiple warehouses to be assigned to a user
                'expanded' => false, // Use a select box instead of checkboxes
                'label' => 'Assigned Warehouses',
                'by_reference' => false,
            ])
            ->add('isAdmin', CheckboxType::class, [
                'label' => 'Grant Administrator Privileges',
                'required' => false,
                'mapped' => false, // This field is not directly mapped to the User entity
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
