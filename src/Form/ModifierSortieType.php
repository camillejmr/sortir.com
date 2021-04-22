<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifierSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, ['label' => ' '])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => ' ',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'dd/mm/yyyy'
                ]])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label' => ' ',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'dd/mm/yyyy'
                ]])
            ->add('nombreInscriptionsMax', null, ['label' => ' '])
            ->add('duree', null, ['label' => ' '])
            ->add('infosSortie', TextareaType::class, ['label' => ' '])


//            ->add('campus', EntityType::class,['class'=>Campus::class,'label'=>' '])
            ->add('lieux', EntityType::class, ['class' => Lieu::class, 'label' => ' ', 'choice_label' => function (Lieu $lieu) {
                return $lieu->getNom() . ' - ' . $lieu->getRue() . ' ' . $lieu->getVilles()->getCodePostal() . ' ' . $lieu->getVilles()->getNomVille();
            }])
            ->add('Enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('Publier', SubmitType::class, ['label' => 'Publier la sortie'])
            ->add('Supprimer', SubmitType::class, ['label' => 'Supprimer la sortie']);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
