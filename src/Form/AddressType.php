<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
				'label' => 'Ville',
                'attr' => [
					'placeholder' => 'Ville',
				],
			])
            ->add('zip', IntegerType::class, [
				'label' => 'Code postal',
				'attr' => [
					'placeholder' => 'Code postal',
				],
			])
            ->add('name', TextType::class, [
				'label' => 'Nom de l\'adresse',
				'attr' => [
					'placeholder' => 'Nom de l\'adresse',
				],
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
