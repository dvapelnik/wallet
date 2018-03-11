<?php


namespace App\ViewHandler;


use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JsonViewHandler
{
    /** @var Serializer */
    private $serializer;

    /**
     * JsonViewHandler constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param ViewHandler $handler
     * @param View $view
     * @param Request $request
     * @param string $format
     * @return JsonResponse
     */
    public function createResponse(ViewHandler $handler, View $view, Request $request, string $format): JsonResponse
    {
        return new JsonResponse(['status' => 'OK', 'code' => 200, 'data' => $this->serializer->toArray($view->getData())]);
    }
}
