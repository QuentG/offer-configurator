<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\Option;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OptionAttributesType extends AbstractType
{
    /**
     * Used to create new option. Then user have to create each attributes related to this option.
     * (We can allow him to choose attributes already existing but it makes no sense right ?)
     **/
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Option::class
        ]);
    }
}
