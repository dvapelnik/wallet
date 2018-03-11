<?php


namespace App\Controller\API;

use App\DataTransferObject as DTO;
use App\Services\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class CategoryController
 * @package App\Controller\API
 *
 * @Route("/categories")
 */
class CategoryController extends AbstractApiController
{
    private const ACTION_CREATE = 'create';
    private const ACTION_UPDATE = 'update';

    /**
     * @Route("/", name="api.category.list", methods={"GET"})
     *
     * @param CategoryService $categoryService
     * @return JsonResponse
     */
    public function listAction(CategoryService $categoryService): JsonResponse
    {
        return $this->makeCorrectJsonResponse($this->serializer->toArray($categoryService->listOfCategories()));
    }

    /**
     * @Route("/", name="api.category.create", methods={"POST"})
     *
     * @param Request $request
     * @param CategoryService $categoryService
     * @return JsonResponse
     * @throws \LogicException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createAction(Request $request, CategoryService $categoryService): JsonResponse
    {
        $constraint = $this->makeCategoryRequestConstraint(self::ACTION_CREATE);

        $validationErrors = $this->validator->validate($request->request->all(), $constraint);

        if (\count($validationErrors) > 0) {
            return $this->makeValidationErrorJsonResponse($validationErrors);
        }

        $data = $request->request->get('data');

        return $this->makeCorrectJsonResponse(
            $this->serializer->toArray(
                $categoryService->createCategory(
                    $this->serializer->fromArray(
                        $data, DTO\Category::class
                    )
                )
            ),
            201
        );
    }

    /**
     * @Route("/{id}", requirements={"id"="^\d+$"}, methods={"DELETE"})
     *
     * @param int $id
     * @param CategoryService $categoryService
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteAction(int $id, CategoryService $categoryService): JsonResponse
    {
        $categoryService->delete($id);

        return $this->makeCorrectJsonResponse();
    }

    /**
     * @Route("/{id}", requirements={"id"="^\d+$"}, methods={"PATCH"})
     *
     * @param Request $request
     * @param CategoryService $categoryService
     * @param int $id
     * @return JsonResponse
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     * @throws \LogicException
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function updateAction(Request $request, CategoryService $categoryService, int $id): JsonResponse
    {
        $constraint = $this->makeCategoryRequestConstraint(self::ACTION_UPDATE);

        $validationErrors = $this->validator->validate($request->request->all(), $constraint);

        if (\count($validationErrors) > 0) {
            $this->makeValidationErrorJsonResponse($validationErrors);
        }

        /** @var DTO\Category $category */
        $category = $this->serializer->fromArray($request->request->get('data'), DTO\Category::class);
        $category->setId($id);

        $category = $categoryService->update($category);

        return $this->makeCorrectJsonResponse($this->serializer->toArray($category));
    }

    /**
     * @param string $action
     * @return Constraint
     * @throws \LogicException
     * @throws \Symfony\Component\Validator\Exception\MissingOptionsException
     * @throws \Symfony\Component\Validator\Exception\InvalidOptionsException
     * @throws \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    private function makeCategoryRequestConstraint(string $action): Constraint
    {
        switch ($action) {
            case self::ACTION_CREATE:
                return new Assert\Collection(
                    [
                        'data' => new Assert\Collection(
                            [
                                'name'      => [
                                    new Assert\Required(),
                                    new Assert\NotBlank(),
                                    new Assert\Type(['type' => 'string']),
                                ],
                                'icon'      => [
                                    new Assert\Required(),
                                    new Assert\NotBlank(),
                                    new Assert\Type(['type' => 'string']),
                                ],
                                'direction' => [
                                    new Assert\Required(),
                                    new Assert\NotBlank(),
                                    new Assert\Type(['type' => 'string']),
                                    new Assert\Choice([
                                        DTO\Category::DERECTION_TYPE_DEBIT,
                                        DTO\Category::DIRECTION_TYPE_CREDIT,
                                    ]),
                                ],
                            ]
                        ),
                    ]
                );
            case self::ACTION_UPDATE:
                return new Assert\Collection(
                    [
                        'data' => [
                            new Assert\NotBlank(),
                            new Assert\Type(['type' => 'array']),
                        ],
                    ]
                );
            default:
                throw new \LogicException("Action '{$action}' is not supported");
        }
    }
}
