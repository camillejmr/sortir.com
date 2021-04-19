<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'dd/mm/yyyy'
            ]])

            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d inscription',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'dd/mm/yyyy'
            ]])

            ->add('nombreInscriptionsMax', IntegerType::class, [
                'label' => 'Nombre de places'
            ])

            ->add('duree', IntegerType::class, [

                'label' => 'Durée (Mn)'
            ])

            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos',
                'required' => false
            ])

            ->add('lieux', EntityType::class, ['class' => Lieu::class,'choice_label'=> function (Lieu $lieu){
                return $lieu-> getNom()." / ".$lieu->getVilles()->getNomVille();
            }
            ]);
            /*->add('organisateur', HiddenType::class, ['class' =>Campus::class,'label' =>function (Campus $id){
                return $id-> getId();
            }
            ]);*/





    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
