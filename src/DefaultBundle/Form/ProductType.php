<?php

namespace DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                'label' => 'Model'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('manufacturer', EntityType::class, [
                "class" => "DefaultBundle:Manufacturer",
                "choice_label" => "Company",
                "label" => "Manufacturer"
            ])
            ->add('category', EntityType::class, [
                "class" => "DefaultBundle:Category",
                "choice_label" => "name",
                "label" => "Category"
            ])
            ->add("iconFileName", FileType::class, [
                "label" => "Icon",
                "mapped" => false //значит что он не будет мапить эти данные
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DefaultBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'defaultbundle_product';
    }


}
