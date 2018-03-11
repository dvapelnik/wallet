<?php


namespace App\Controller\API;


use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController
{
    /** @var Serializer */
    protected $serializer;

    /** @var ValidatorInterface */
    protected $validator;

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
     * @param array $data
     * @param int $code
     * @param string $status
     *
     * @return JsonResponse
     */
    protected function makeCorrectJsonResponse(array $data = [], int $code = 200, string $status = "OK"): JsonResponse
    {
        return new JsonResponse(
            [
                'status' => $status,
                'code'   => $code,
                'data'   => $data,
            ],
            $code
        );
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
