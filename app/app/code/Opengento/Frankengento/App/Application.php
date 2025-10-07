<?php

declare(strict_types=1);

namespace Opengento\Frankengento\App;

use Exception;
use InvalidArgumentException;
use Magento\Framework\App;
use Magento\Framework\App\ExceptionHandlerInterface;
use Magento\Framework\App\FrontControllerInterface as FrontController;
use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\AppInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Event\Manager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;

class Application implements AppInterface
{
    public function __construct(
        private ObjectManagerInterface $objectManager,
        private Manager $eventManager,
        private Registry $registry,
        private ExceptionHandlerInterface $exceptionHandler,
        private Http $response,
        private App\Request\Http $request,
    ) {}

    /**
     * @inheritDoc
     */
    public function launch(): HttpInterface
    {
        /** @var FrontController $frontController */
        $frontController = $this->objectManager->create(FrontController::class);

        $response = $this->handleHead(
            $this->request,
            $this->handleResponse($frontController->dispatch($this->request))
        );

        // This event gives possibility to launch something before sending output (allow cookie setting)
        $this->eventManager->dispatch(
            'controller_front_send_response_before',
            ['request' => $this->request, 'response' => $response]
        );

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function catchException(App\Bootstrap $bootstrap, Exception $exception): bool
    {
        return $this->exceptionHandler->handle($bootstrap, $exception, $this->response, $this->request);
    }

    private function handleResponse(ResultInterface|HttpInterface $result): ResultInterface|HttpInterface
    {
        // TODO: Temporary solution until all controllers return ResultInterface (MAGETWO-28359);
        return match (true) {
            $result instanceof ResultInterface => $this->handleLayoutResult($result),
            $result instanceof HttpInterface => $this->handleHttpResult($result),
            default => throw new InvalidArgumentException('Invalid return type.')
        };
    }

    private function handleLayoutResult(ResultInterface $result): ResultInterface|HttpInterface
    {
        $this->registry->register('use_page_cache_plugin', true, true);
        $result->renderResult($this->response);

        return $this->response;
    }

    private function handleHttpResult(HttpInterface $result): ResultInterface|HttpInterface
    {
        $this->response->setContent($result->getContent());
        if ($this->response !== $result) { //do not double headers
            $this->response->getHeaders()?->addHeaders($result->getHeaders());
        }

        return $this->response;
    }

    private function handleHead(HttpRequestInterface $request, HttpInterface $response): HttpInterface
    {
        if ($request->isHead() && $response->getHttpResponseCode() === 200) {
            $contentLength = mb_strlen($response->getContent(), '8bit');
            $response->clearBody();
            $response->setHeader('Content-Length', $contentLength);
        }

        return $response;
    }
}
