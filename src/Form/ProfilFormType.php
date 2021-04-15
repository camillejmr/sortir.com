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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('Pseudo',null, ['attr'=>array('required'=>true, 'value'=> Participant::getPseudo()),
            ])
            ->add('Prenom',null, ['attr'=>array('required'=>true, 'value'=>'Test'),
            ])
            ->add('Nom',null, ['attr'=>array('required'=>true, 'value'=>'Test'),
            ])
            ->add('mail',null, ['attr'=>array('required'=>true, 'value'=>'Test'),
            ])

            ->add('Telephone',null, ['attr'=>array('required'=>true, 'value'=>'Test'),
            ])

            ->add('password', PasswordType::class, [
                    'label' => 'Mot de passe',
             'empty_data' => ''
            ])

      ->add('Campus', EntityType::class, ['class' => Campus::class, 'choice_label'=>'nomCampus'])




            // UPLOAD PHOTO

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
