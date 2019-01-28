<?php

namespace App\Form;

use App\Entity\Prof;
use App\Entity\Module;
use App\Repository\ModuleRepository;
use App\Repository\ProfRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $profs = $options['profs'];
        $builder
            ->add('nom')
            ->add('vol_horaire')
            ->add('coefficient')
            ->add('enseignant', EntityType::class, [
                'class' => Prof::class,
                'choice_label' => 'username',
            ])

//            ->add('enseignant', ChoiceType::class, [
//                'choices' => [
//                    foreach ($profs as $prof)
//                    {
//                        echo $prof->getNom() . " => " . $prof . ",";
//                    }
//                ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
