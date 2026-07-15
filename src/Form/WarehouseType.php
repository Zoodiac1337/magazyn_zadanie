<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Warehouse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WarehouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Warehouse Name',
                'required' => true,
            ])
            // Assign a user to the warehouse. This will create a ManyToMany relationship between Warehouse and User.
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true, // Allow multiple users to be assigned to a warehouse
                'expanded' => false, // Use a select box instead of checkboxes
                'label' => 'Assigned Users',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Warehouse::class,
        ]);
    }
}
