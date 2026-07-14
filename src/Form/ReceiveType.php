<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Warehouse;
use App\Entity\WarehouseOperation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity Received',
                'empty_data' => '1', // Default value for quantity
                'required' => false, // Allow the field to be empty to allow default value to be used
                'attr' => [
                    'min' => 1,
                    'placeholder' => 1,
                ],
            ])
            ->add('vat', IntegerType::class, [
                'label' => 'VAT Rate (%)',
                'empty_data' => '23', // Default value for VAT
                'required' => false, // Allow the field to be empty to allow default value to be used
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                    'placeholder' => 23,
                ],
                
            ])
            ->add('priceNetto', NumberType::class, [
            'label' => 'Unit Price (Net)',
            'scale' => 2,
            'html5' => true,
            'attr' => [
                'placeholder' => '0.00',
                'step' => '0.01',
                'min' => '0.00',
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
