<?php

namespace App\Doctrine\Constraint\EntityExists;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function validate($entityIdentifier, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new \InvalidArgumentException(sprintf('Constraint must be instance %s', EntityExists::class));
        }

        if (empty($constraint->entityClass) || false === $this->objectManager->getMetadataFactory()->isTransient($constraint->entityClass)) {
            throw new \InvalidArgumentException(sprintf(
                '"entityClass" must be real entity class. Not found mapping for "%s"',
                $constraint->entityClass
            ));
        }

        $repository = $this->objectManager->getRepository($constraint->entityClass);
        $entity = $entityIdentifier ? $repository->find($entityIdentifier) : null;

        if ($entity === null) {
            $this->context->addViolation($constraint->message);
        }
    }
}
