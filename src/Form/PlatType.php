<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
				'label' => 'Nom du plat',
                'attr' => [
					'placeholder' => 'Nom du plat',
                ],
			])
            ->add('price', IntegerType::class, [
				'label' => 'Prix du plat',
				'attr' => [
					'placeholder' => 'Prix du plat',
				],
            ])
	        ->add('ingredients', EntityType::class, [
		        'label' => 'Ingrédients',
		        'mapped' => true,
		        'class' => Ingredient::class,
		        'choice_label' => 'name',
		        'multiple' => true,
		        'expanded' => true,
	        ])
	        ->add('submit', SubmitType::class, [
		        'label' => 'Ajouter',
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
