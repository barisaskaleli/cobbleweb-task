<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    /**
     * @var ValidatorInterface $validator
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $object
     * @return array
     */
    public function validate($object): array
    {
        $errors = $this->validator->validate($object);
        $errorsArray = [];
        foreach ($errors as $error) {
            $errorsArray[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorsArray;
    }
}