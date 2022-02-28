<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;

final class OrderAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('clientName')
            ->add('phoneNumber')
            ->add('email')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('clientName')
            ->add('phoneNumber')
            ->add('email')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            // ->add('id')
            ->add('clientName')
            ->add('phoneNumber')
            ->add('email')
            // ->add('orderProducts')
            // ->add('orderProducts', CollectionType::class, [
            //         'by_reference' => false
            //     ],
            //     [
            //         'edit' => 'inline',
            //         'inline' => 'table',
            //     ]
            // )
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('clientName')
            ->add('phoneNumber')
            ->add('email')
            ;
    }
}
