<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Warehouse;
use App\Entity\WarehouseOperation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity Issued',
                'empty_data' => '1', // Default value for quantity
                'required' => false, // Allow the field to be empty to allow default value to be used
                'attr' => [
                    'min' => 1,
                    'placeholder' => 1,
                ],
            ])
            ->add('warehouse', EntityType::class, [
                'class' => Warehouse::class,
                'choice_label' => 'name',
                'placeholder' => '-- Select Warehouse --',
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'placeholder' => '-- Select Product --',
                'choice_attr' => function(Product $product) {
                    return ['data-unit' => $product->getUnit()];
                    },
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WarehouseOperation::class,
        ]);
    }
}
