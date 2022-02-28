<?php

namespace App\Form;

use App\Entity\Wish;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThan;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $minAge = new DateTime('now');
        $minAge ->modify('-18 years');
        $builder
            ->add('title')
            ->add('description')
            ->add('author')
            ->add('Dob', DateType::class,[
                'required' => true,
                "mapped" => false,
                'widget' => 'single_text',
                'format'=>'yyyy-MM-dd',
                'constraints' => [new LessThan($minAge)],
            ],)
            ->add('isPublished');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
