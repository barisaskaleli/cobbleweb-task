<?php

namespace App\Command;

use App\Service\NewsletterMailerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NewsletterMailerCommand extends Command
{
    protected static $defaultName = 'newsletter-mailer';
    protected static $defaultDescription = 'This command will send newsletter to all active users created in the last week.';

    public function __construct(private NewsletterMailerService $newsletterMailerService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->newsletterMailerService->getUsers();

        if (empty($users)) {
            $io->warning('No users found to send newsletter to.');

            return 0;
        }

        $failedRecipients = $this->newsletterMailerService->sendNewsletter($users);

        if (!empty($failedRecipients)) {
            $io->warning(sprintf('Failed to send newsletter to %s users. You may want to check!', \count($failedRecipients)));
            $io->listing($failedRecipients);
        }

        $io->success('Newsletter sent successfully.');

        return 1;
    }
}
