<?php

namespace Guapit\PublicBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->add('email', 'email');
    $builder->add('plainPassword', 'repeated', array(
        'first_name'  => 'password',
        'second_name' => 'confirm',
        'type'        => 'password',
    ));
    $builder->add('first_name', 'text');
    $builder->add('last_name', 'text');
    $builder->add('university', 'text');
    $builder->add('speciality', 'text');
    $builder->add('course', 'integer');
    $builder->add('phone', 'text');
    $builder->add('role', 'integer');

}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        'data_class' => 'Guapit\PublicBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}