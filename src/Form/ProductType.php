<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label'=>'Nombre del producto'])
            ->add('description', TextareaType::class, ['label'=>'Descripción del producto'])
            ->add('photo', FileType::class, [
                'label'=>'Seleccione una foto para el producto',
                'mapped' => false,
                'required'=>false
            ])
            ->add('price', IntegerType::class, ['label'=>'Precio del producto'])
            ->add('discount', IntegerType::class,['label'=>'Descuento del producto (Porcentaje)'])
            ->add('category', EntityType::class, [
                'label'=>'Categoría',
                'class'=>Category::class,
                'choice_label' => 'name',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
