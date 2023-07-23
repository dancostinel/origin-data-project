<?php

namespace App\Command;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:generate-users',
    description: 'This command adds users into database for testing purposes.',
)]
class GenerateUsersCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        foreach (range(2, 5) as $userId) {
            $user = new User();
            $user
                ->setIsVerified(true)
                ->setEmail('test'.$userId.'@origin-data.com')
                ->setPassword($this->userPasswordHasher->hashPassword($user, '123456'));
            $this->entityManager->persist(new ApiToken($user));
            $this->entityManager->persist($user);
        }
        try {
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success('User #'.$user->getId().' has been created successfully');

        return Command::SUCCESS;
    }
}
