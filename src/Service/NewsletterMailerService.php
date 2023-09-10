<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Trait\LoggerTrait;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class NewsletterMailerService
{
    use LoggerTrait;

    private const SENDER_EMAIL = 'test@test.com';
    private const SENDER_NAME = 'Cobbleweb';
    private const SUBJECT = 'Your best newsletters';

    /**
     * @param MailerInterface $mailer
     * @param UserRepository $userRepository
     */
    public function __construct(private MailerInterface $mailer, private UserRepository $userRepository)
    {
    }

    /**
     * @param array $users
     * @return array
     * @throws TransportExceptionInterface
     */
    public function sendNewsletter(array $users): array
    {
        $failedRecipients = [];

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            $mail = $this->prepareMail($user);

            try {
                $this->mailer->send($mail);
            } catch (\Exception $e) {
                $failedRecipients[] = $user->getEmail();
                $this->logger->error(sprintf('Error sending newsletter to user %s: %s', $user->getEmail(), $e->getMessage()));
            }
        }

        return $failedRecipients;
    }

    /**
     * @param User $user
     * @return Email
     */
    private function prepareMail(User $user)
    {
        $messageBody = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.';

        return (new Email())
            ->from(new Address(self::SENDER_EMAIL, self::SENDER_NAME))
            ->subject(self::SUBJECT)
            ->to($user->getEmail())
            ->text($messageBody)
            ->html($messageBody);
    }

    /**
     * @return User
     */
    public function getUsers(): array
    {
        return $this->userRepository->getAllActiveUsersCreatedInLastWeek();
    }
}