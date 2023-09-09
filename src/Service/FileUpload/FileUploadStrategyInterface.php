<?php

namespace App\Service\FileUpload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploadStrategyInterface
{
    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public function upload(UploadedFile $file, string $fileName);

    /**
     * @return string
     */
    public function name(): string;
}