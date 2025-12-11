<?php

namespace App\Controller\Admin;

use App\Entity\House;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class HouseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return House::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('address', 'Address'),
            NumberField::new('area', 'Area (m²)'),
            NumberField::new('price', 'Price ($)'),
            NumberField::new('bedrooms', 'Bedrooms'),
            NumberField::new('distanceToSea', 'Distance to Sea (m)'),
            BooleanField::new('hasShower', 'Has Shower'),
            BooleanField::new('hasBathroom', 'Has Bathroom'),
        ];
    }
}
