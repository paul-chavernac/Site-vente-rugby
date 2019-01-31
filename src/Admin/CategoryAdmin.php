<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CategoryAdmin extends AbstractAdmin
{
protected function configureFormFields(FormMapper $formMapper)
{
$formMapper->add('libelle', TextType::class);
}

protected function configureDatagridFilters(DatagridMapper $datagridMapper)
{
$datagridMapper->add('libelle');
}

protected function configureListFields(ListMapper $listMapper)
{
$listMapper->addIdentifier('libelle');
}
}