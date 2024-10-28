<?php

namespace App\Controller\Admin;

use App\Entity\GitUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 *
 */
class GitUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GitUser::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Name', 'Name'),
            TextField::new('gitGroup', 'Group'),
            TextField::new('gitProject', 'Project'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('gitGroup')
            ->add('gitProject');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE);
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->setEntityLabelInSingular('Git User')
            ->setEntityLabelInPlural('Git Users')
            ->showEntityActionsInlined()
            ;
    }
}
