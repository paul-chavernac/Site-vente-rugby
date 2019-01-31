<?php

namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('id', TextType::class);
        $formMapper->add('username', TextType::class);
        $formMapper->add('password', TextType::class);
        $formMapper->add('email', TextType::class);
        $CategFieldOptions = [];
        $formMapper->add('roles', ModelListType::class, $CategFieldOptions);

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');
        $datagridMapper->add('username');
        $datagridMapper->add('password');
        $datagridMapper->add('email');
        $datagridMapper->add('roles');

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('username');
        $listMapper->addIdentifier('password');
        $listMapper->addIdentifier('email');
        $listMapper->addIdentifier('roles');

    }
}