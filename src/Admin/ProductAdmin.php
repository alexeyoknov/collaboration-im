<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class ProductAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('created')
            ->add('updated')
            ->add('price')
            ->add('active')
            ->add('gallery')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('Category')
            ->add('created')
            ->add('updated')
            ->add('price')
            ->add('active')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
            ->add('gallery')
            ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            //->add('id')
            ->add('name')
            ->add('description',TextareaType::class, ['attr'=>['class'=>'ckeditor']])
            ->add('Category')
            //->add('created')
            //->add('updated')
            ->add('price')
            ->add('active')
            ->add('gallery')
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('created')
            ->add('updated')
            ->add('price')
            ->add('active')
            ->add('gallery')
            ;
    }
}
