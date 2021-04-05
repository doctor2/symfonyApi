<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ValidationFailedException) {
            return;
        }

        $responseData = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'errors' => $this->normalizeErrors($exception->getViolations()),
        ];

        $event->allowCustomResponseCode();

        $event->setResponse(new JsonResponse($responseData));
    }

    /**
     * {@inheritDoc}
     */
    private function normalizeErrors(ConstraintViolationListInterface $constraintViolations): array
    {
        $errors = [];

        foreach ($constraintViolations as $constraintViolation) {
            $errors[$constraintViolation->getPropertyPath()][] = $constraintViolation->getMessage();
        }

        return $errors;
    }
}
