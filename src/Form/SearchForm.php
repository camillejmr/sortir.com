<?php


namespace App\Form;


use App\Data\SearchData;
use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // TODO : campus
            /*->add('campus', EntityType::class, [
                'label' => 'Campus',
            ])*/

            ->add('champRecherche', TextType::class, [
                'label' => 'Le nom de la sortie contient : ',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])

            ->add('dateMin', DateTimeType::class, [
                'label' => 'Entre',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => ' /  /  '
                ]
            ])
            ->add('dateMax', DateTimeType::class, [
                'label' => 'et',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => ' /  /  '
                ]
            ])
            ->add('estOrganisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('estInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('estNonInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('sortieTerminee', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',  // Pour que les paramètres de recherches apparaissent dans l'URL et que l'utilisateur puisse partager sa recherche
            'csrf_protection' => false  // Pas besoin
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; // Pour une URL propre
    }

}
