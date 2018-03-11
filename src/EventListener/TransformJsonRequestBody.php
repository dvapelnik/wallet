<?php


namespace App\EventListener;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TransformJsonRequestBody
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * TransformJsonRequestBody constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     * @throws \LogicException
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        $contentType = $request->getContentType();

        $this->logger->info('REQUEST CONTENT TYPE', ['content-type' => $contentType]);

        if (\in_array($contentType, ['json', 'application/json'], true)) {
            $json = json_decode($request->getContent(), true);

            foreach ($json as $key => $value) {
                $request->request->set($key, $value);
            }

            $this->logger->info('REQUEST TRANSFORMED');
        }
    }
}
