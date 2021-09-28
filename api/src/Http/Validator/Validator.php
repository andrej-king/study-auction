<?php

declare(strict_types=1);

namespace App\Http\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validate object by symfony validate.
 */
class Validator
{
    private ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate object.
     *
     * @param object $object
     *
     * @throws ValidationException
     */
    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }
}
