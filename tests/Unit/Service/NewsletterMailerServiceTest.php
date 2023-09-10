<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\NewsletterMailerService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;

final class NewsletterMailerServiceTest extends TestCase
{
    private const USER_MAIL = 'user1@example.com';
    private const SECOND_USER_MAIL = 'user2@example.com';

    private $mailerMock;
    private $userRepositoryMock;
    private $loggerMock;

    protected function setUp(): void
    {
        $this->mailerMock = $this->createMock(MailerInterface::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
    }

    public function testSendNewsletter()
    {
        $user1 = $this->createMock(User::class);
        $user1->method('getEmail')->willReturn(self::USER_MAIL);

        $user2 = $this->createMock(User::class);
        $user2->method('getEmail')->willReturn(self::SECOND_USER_MAIL);

        $users = [$user1, $user2];

        $newsletterMailerService = new NewsletterMailerService($this->mailerMock, $this->userRepositoryMock);
        $newsletterMailerService->setLogger($this->loggerMock);

        $this->mailerMock
            ->expects(static::exactly(2))
            ->method('send')
            ->willReturnOnConsecutiveCalls(null, static::throwException(new \Exception('Error sending newsletter to user')));

        $failedRecipients = $newsletterMailerService->sendNewsletter($users);

        static::assertCount(1, $failedRecipients);
        static::assertEquals(self::SECOND_USER_MAIL, $failedRecipients[0]);
    }

    public function testGetUsers()
    {
        $user1 = $this->createMock(User::class);
        $user2 = $this->createMock(User::class);

        $this->userRepositoryMock
            ->expects(static::once())
            ->method('getAllActiveUsersCreatedInLastWeek')
            ->willReturn([$user1, $user2]);

        $newsletterMailerService = new NewsletterMailerService($this->mailerMock, $this->userRepositoryMock);

        $users = $newsletterMailerService->getUsers();

        static::assertCount(2, $users);
    }
}