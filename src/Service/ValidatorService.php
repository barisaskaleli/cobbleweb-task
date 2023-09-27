<?php

namespace App\Service;

use App\Exception\ValidationException;
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
     * @param $photos
     * @return array
     */
    public function validate($object, $photos): array
    {
        $errors = $this->validator->validate($object);
        $errorsArray = [];
        foreach ($errors as $error) {
            $errorsArray[$error->getPropertyPath()] = $error->getMessage();
        }

        $errorsArray = $this->validatePhotos($errorsArray, $photos);

        return $errorsArray;
    }

    private function validatePhotos($errorsArray, $photos): array
    {
        if (\count($photos) < 4) {
            $errorsArray['photos'] = ValidationException::PHOTOS_VALIDATION_ERROR;
        }

        return $errorsArray;
    }
}