<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class ProduitAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('id', TextType::class);
        $formMapper->add('titre', TextType::class);
        $formMapper->add('prix', TextType::class);
        $CategFieldOptions = [];
        $formMapper->add('categorieProduit', ModelListType::class, $CategFieldOptions);

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id');
        $datagridMapper->add('titre');
        $datagridMapper->add('prix');
        $datagridMapper->add('categorieProduit');


    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');
        $listMapper->addIdentifier('titre');
        $listMapper->addIdentifier('prix');
        $listMapper->addIdentifier('categorieProduit');
    }
}