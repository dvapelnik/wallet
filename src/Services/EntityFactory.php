<?php


namespace App\Services;


use App\DataTransferObject as DTO;
use App\Entity as Entity;

class EntityFactory
{
    /**
     * @param DTO\Category $category
     * @return Entity\Category
     */
    public function makeCategory(DTO\Category $category): Entity\Category
    {
        return (new Entity\Category())
            ->setName($category->getName())
            ->setIcon($category->getIcon())
            ->setDirection($category->getDirection());
    }
}
