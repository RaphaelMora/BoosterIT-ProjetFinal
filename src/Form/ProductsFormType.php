<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix :',
                'divisor' => 100,
                'constraints' => [
                    new Positive([
                        'message' => "Le prix ne peut pas être négatif",
                    ]),
                ],
            ])
            ->add('stock', TextType::class, [
                'label' => 'Stock :',
                'constraints' => [
                    new Positive([
                        'message' => "Le stock ne peut pas être négatif",
                    ]),
                ],
            ])
            ->add(
                'categories',
                EntityType::class,
                [
                    'label' => 'Catégorie :',
                    'class' => Categories::class,
                    'choice_label' => 'name',
                    'group_by' => 'parent.name',
                    'query_builder' => function (CategoriesRepository $cr) {
                        return $cr->createQueryBuilder('c')
                            ->where('c.parent IS NOT NULL')
                            ->orderBy('c.name', 'ASC');
                    },
                ]
            )
            ->add('images', FileType::class, [
                'label' => 'Product Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
