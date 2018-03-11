<?php


namespace App\Services;


use App\DataTransferObject as DTO;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityNotFoundException;

class CategoryService
{
    /** @var CategoryRepository */
    private $categoryRepository;

    /** @var DTOFactory */
    private $dtoFactory;

    /** @var EntityFactory */
    private $entityFactory;

    /** @var string[] */
    private static $mutableFields = ['name', 'icon', 'direction'];

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     * @param DTOFactory $dtoFactory
     * @param EntityFactory $entityFactory
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        DTOFactory $dtoFactory,
        EntityFactory $entityFactory
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->dtoFactory = $dtoFactory;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @param DTO\Category $category
     * @return DTO\Category
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCategory(DTO\Category $category): DTO\Category
    {
        return $this->dtoFactory->makeCategory(
            $this->categoryRepository->create(
                $this->entityFactory->makeCategory($category)
            )
        );
    }

    /**
     * @param DTO\Category $categoryDto
     * @return DTO\Category
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(DTO\Category $categoryDto): DTO\Category
    {
        $categoryEntity = $this->categoryRepository->find($categoryDto->getId());

        if (null === $categoryEntity) {
            throw new EntityNotFoundException("Category with id '{$categoryDto->getId()}' not found");
        }

        foreach (self::$mutableFields as $mutableField) {
            $getterName = 'get'.ucfirst($mutableField);

            $newValue = $categoryDto->{$getterName}();
            $oldValue = $categoryEntity->{$getterName}();

            if (null !== $newValue && $newValue !== $oldValue) {
                $setterName = 'set'.ucfirst($mutableField);

                $categoryEntity->{$setterName}($newValue);
                $categoryEntity->setUpdatedAt(new \DateTime());
            }
        }

        $this->categoryRepository->update($categoryEntity);

        return $this->dtoFactory->makeCategory($categoryEntity);
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(int $id): void
    {
        $this->categoryRepository->delete($id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Dto\Category[]
     */
    public function listOfCategories(int $limit = 10, int $offset = 0): array
    {
        return $this->dtoFactory->makeCategories($this->categoryRepository->findPaged($limit, $offset));
    }
}
