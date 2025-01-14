<?php
namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteInactiveUsersCommand extends Command
{
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(name: 'app:delete-inactive-users')  // Nom explicite de la commande
            ->setDescription(description: 'Supprime les utilisateurs dont le compte est désactivé depuis un mois.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTime();
        $users = $this->userRepository->findUsersToDelete($now);

        foreach ($users as $user) {
            $this->em->remove(object: $user);
        }

        $this->em->flush();

        $output->writeln(messages: 'Utilisateurs supprimés avec succès.');

        return Command::SUCCESS; // Retourne un code de succès pour la commande
    }
}
