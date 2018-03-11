<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param Category $category
     *
     * @return Category
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Category $category): Category
    {
        $em = $this->getEntityManager();

        $em->persist($category);
        $em->flush($category);

        return $category;
    }

    /**
     * @param int $id
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(int $id): void
    {
        $category = $this->find($id);

        if (null === $category){
            throw new EntityNotFoundException("Category with id '{$id}' not found");
        }

        $em = $this->getEntityManager();

        $em->remove($category);
        $em->flush($category);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Category[]
     */
    public function findPaged(int $limit, int $offset = 0): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Category $categoryEntity
     * @return Category
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Category $categoryEntity): Category
    {
        $this->getEntityManager()->flush($categoryEntity);

        return $categoryEntity;
    }
}
