<?php


namespace App\Controller\API;


use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\ViewHandlerInterface;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController
{
    use ControllerTrait;

    /** @var ViewHandler */
    private $viewHandler;

    /** @var Serializer */
    protected $serializer;

    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @param ViewHandler $viewHandler
     * @return $this
     */
    public function setViewHandler(ViewHandler $viewHandler): AbstractApiController
    {
        $this->viewHandler = $viewHandler;

        return $this;
    }

    /**
     * Get the ViewHandler.
     *
     * @return ViewHandlerInterface
     */
    protected function getViewHandler()
    {
        return $this->viewHandler;
    }

    /**
     * @param Serializer $serializer
     * @return $this
     */
    public function setSerializer(Serializer $serializer): AbstractApiController
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator): AbstractApiController
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $code
     * @param string $status
     * @return JsonResponse
     */
    protected function makeIncorrectJsonReponse(
        string $message,
        array $errors,
        int $code = 400,
        string $status = "ERROR"
    ): JsonResponse {
        return new JsonResponse(
            [
                'status'  => $status,
                'code'    => $code,
                'message' => $message,
                'errors'  => $errors,
            ],
            $code
        );
    }

    /**
     * @param ConstraintViolationListInterface $validationErrors
     * @return JsonResponse
     */
    protected function makeValidationErrorJsonResponse(ConstraintViolationListInterface $validationErrors): JsonResponse
    {
        $errors = [];
        foreach ($validationErrors as $validationError) {
            /** @var ConstraintViolation $validationError */
            $errors[] = [
                'message'  => $validationError->getMessage(),
                'location' => trim(str_replace('][', '.', $validationError->getPropertyPath()), ']['),
            ];
        }

        return $this->makeIncorrectJsonReponse('Validation error', $errors);
    }
}
