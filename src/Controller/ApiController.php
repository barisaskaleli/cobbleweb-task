<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Schema\RegisterResponseSchema;
use App\Service\FileUpload\UploadContext;
use App\Service\FileUpload\UploadStrategy\LocalUploadStrategy;
use App\Service\FileUpload\UploadStrategy\S3UploadStrategy;
use App\Service\ValidatorService;
use App\Trait\EntityManagerAwareTrait;
use Aws\Sdk;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api", name="api_", defaults={"_format": "json"})
 */
class ApiController extends AbstractController
{
    use EntityManagerAwareTrait;

    /**
     * @var ValidatorService
     */
    private $validator;

    /**
     * @var Sdk $aws
     */
    private $aws;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(ValidatorService $validator, Sdk $aws, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->validator = $validator;
        $this->aws = $aws;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/users/login", name="login", methods={"POST"})
     */
    public function login()
    {
        // This code will not be executed, as the route is handled by the LexikJWTAuthenticationBundle
    }

    /**
     * @Route("/users/register", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        $user = (new User())
            ->setFirstName($request->request->get('first_name') ?? '')
            ->setLastName($request->request->get('last_name') ?? '')
            ->setFullName()
            ->setEmail($request->request->get('email') ?? '')
            ->setActive($request->request->get('active') ?? false)
            ->setAvatar($request->request->get('avatar') ?? '');

        $validation = $this->validator->validate($user);

        $response = new RegisterResponseSchema();

        // if the validation fails, return the error message
        if ($validation) {
            return JsonResponse::create(
                $response
                    ->setFailedStatus()
                    ->setMessage(ValidationException::VALIDATION_ERROR)
                    ->setStatusCode(JsonResponse::HTTP_BAD_REQUEST)
                    ->getResponse($validation)
                , JsonResponse::HTTP_BAD_REQUEST);
        }

        // if the validation is successful, encode the password
        $hashedPass = $this->passwordEncoder->encodePassword($user, $request->request->get('password'));
        $user->setPassword($hashedPass);

        $publicDirectory = $this->getParameter('public_directory');
        // create a new upload context
        $uploadContext = new UploadContext();

        // add the strategies to the context
        $uploadContext->addStrategy(new LocalUploadStrategy($publicDirectory));
        $uploadContext->addStrategy(new S3UploadStrategy($this->aws, $publicDirectory));

        $photos = $request->files->get('photos');

        if ($photos) {
            foreach ($photos as $photoFile) {
                // upload the photo to the selected strategies
                $photoResult = $uploadContext->upload($photoFile);

                $photoObj = (new Photo())
                    ->setName($photoResult['s3']['name'])
                    ->setUrl($photoResult['s3']['path'])
                    ->setUser($user);

                $user->addPhoto($photoObj);
            }
        }

        $avatar = $request->files->get('avatar');

        if ($avatar) {
            // upload the avatar to the selected strategies
            $avatarResult = $uploadContext->upload($avatar);

            $user->setAvatar($avatarResult['s3']['path']);
        }

        // persist the user and flush the changes
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return JsonResponse::create(
            $response
                ->setSuccessStatus()
                ->setMessage('User created successfully')
                ->setStatusCode(JsonResponse::HTTP_CREATED)
                ->getResponse()
            , JsonResponse::HTTP_CREATED);
    }
}