<?php
// src/Command/AddUserCommand.php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Add a new user to the database.',
)]
class AddUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'Plain password of the user')
            ->addArgument('first_name', InputArgument::REQUIRED, 'First name of the user')
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $user->setRoles(['ROLE_USER']); // Définir les rôles par défaut
        $user->setFirstName($firstName);
        $user->setLastName($lastName);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>User added successfully!</info>');

        return Command::SUCCESS;
    }
}
