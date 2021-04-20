<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutLieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du lieu'
            ])
            ->add('rue', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('latitude', IntegerType::class, [
                'label' => 'Latitude'
            ])
            ->add('longitude', IntegerType::class, [
                'label' => 'Longitude'
            ])
            ->add('villes', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => function (Ville $nomVille){
                    return $nomVille-> getNomVille();
            }
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
