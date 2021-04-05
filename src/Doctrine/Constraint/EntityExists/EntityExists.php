<?php

namespace App\Doctrine\Constraint\EntityExists;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EntityExists extends Constraint
{
    public $message = 'Entity not found';
    public $entityClass;

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
