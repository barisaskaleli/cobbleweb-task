<?php

namespace App\Service\FileUpload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadContext
{

    private $strategies = [];

    /**
     * @param FileUploadStrategyInterface $strategy
     * @return void
     */
    public function addStrategy(FileUploadStrategyInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param UploadedFile $file
     * @return void
     */
    public function upload(UploadedFile $file): array
    {
        $strategyResult = [];
        $fileName = $this->generateFileName($file);

        /**
         * @var FileUploadStrategyInterface $strategy
         */
        foreach ($this->strategies as $strategy) {
            $strategyResult[$strategy->name()] = $strategy->upload($file, $fileName);
        }

        return $strategyResult;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function generateFileName(UploadedFile $file): string
    {
        return md5(uniqid('', true)) . '.' . $file->guessExtension();
    }
}