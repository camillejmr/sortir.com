<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use http\Client\Curl\User;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('Pseudo',null )
            ->add('Prenom',null, ['attr'=>array('required'=>true),
            ])
            ->add('Nom',null, ['attr'=>array('required'=>true),
            ])
            ->add('Mail',null, ['attr'=>array('required'=>true),
            ])

            ->add('Telephone',null, ['attr'=>array('required'=>true),
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation sont différents.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])

      ->add('Campus', EntityType::class, ['class' => Campus::class, 'choice_label'=>'nomCampus'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
