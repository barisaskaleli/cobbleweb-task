<?php

namespace App\Service\FileUpload\UploadStrategy;

use App\Exception\UploadException;
use App\Service\FileUpload\FileUploadStrategyInterface;
use Aws\Sdk;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3UploadStrategy implements FileUploadStrategyInterface
{
    private const BUCKET_NAME = 'cw-recruitment-tests';

    private $s3;

    private $targetDirectory;

    public function __construct(Sdk $aws, string $targetDirectory)
    {
        $this->s3 = $aws->createS3();
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, string $fileName): array
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => self::BUCKET_NAME,
                'Key' => $fileName,
                'Body' => fopen(sprintf('%s/%s', $this->targetDirectory, $fileName), 'rb'),
                'ACL' => 'public-read',
            ]);

            return [
                'path' => $result['ObjectURL'],
                'name' => $fileName
            ];
        } catch (\Exception $e) {
            throw new UploadException(sprintf('S3 Upload Error: %s', $e->getMessage()));
        }
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 's3';
    }
}