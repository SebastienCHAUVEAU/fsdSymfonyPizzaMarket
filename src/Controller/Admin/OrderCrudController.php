<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\ItemOrderType;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $config = parent::configureFields($pageName);
        $config[] = 
            CollectionField::new('itemOrders')
            ->setEntryType(ItemOrderType::class)
            ->allowAdd(true);
            
        return $config;
    }
    
}
