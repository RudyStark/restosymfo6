<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
				'label' => 'Nom du menu',
				'attr' => [
					'placeholder' => 'Nom du menu',
				],
			])
            ->add('price', IntegerType::class, [
				'label' => 'Prix du menu',
                'attr' => [
					'placeholder' => 'Prix du menu',
				],
			])
            ->add('plats', EntityType::class, [
				'label' => 'Plats',
				'mapped' => true,
				'class' => Plat::class,
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
            'data_class' => Menu::class,
        ]);
    }
}
