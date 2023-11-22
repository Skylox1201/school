<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('eleve', EntityType::class, [
                'class' => Eleve::class,
                'choice_label' => function (Eleve $eleve) {
                    return $eleve->getFirstName() . ' ' . $eleve->getLastName();
                },
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => function (Matiere $matiere) {
                    return $matiere->getName() . ' - ' . $matiere->getCoefficient();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
