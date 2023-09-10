<?php

namespace App\Service\FileUpload\UploadStrategy;

use App\Exception\UploadException;
use App\Service\FileUpload\FileUploadStrategyInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalUploadStrategy implements FileUploadStrategyInterface
{
    private const STRATEGY_NAME = 'local';

    /**
     * @var string $targetDirectory
     */
    private $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param $file
     * @return string
     * @throws UploadException
     */
    public function upload(UploadedFile $file, string $fileName): array
    {
        try {
            $file->move($this->targetDirectory, $fileName);

            return [
                'path' => $this->targetDirectory . '/' . $fileName,
                'name' => $fileName,
            ];
        } catch (\Exception $e) {
            throw new UploadException(sprintf('Local Upload Error: %s', $e->getMessage()));
        }
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return self::STRATEGY_NAME;
    }
}