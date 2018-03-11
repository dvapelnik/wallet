<?php


namespace App\Services;


use App\DataTransferObject as DTO;
use App\Entity as Entity;

class DTOFactory
{
    /**
     * @param Entity\Category $category
     * @return DTO\Category
     */
    public function makeCategory(Entity\Category $category): DTO\Category
    {
        return new DTO\Category(
            $category->getId(),
            $category->getName(),
            $category->getIcon(),
            $category->getDirection(),
            $category->getCreatedAt(),
            $category->getUpdatedAt()
        );
    }

    /**
     * @param Entity\Category[] $categories
     * @return DTO\Category[]
     */
    public function makeCategories(array $categories): array
    {
        return array_map(
            function (Entity\Category $category) {
                return $this->makeCategory($category);
            },
            $categories
        );
    }
}
