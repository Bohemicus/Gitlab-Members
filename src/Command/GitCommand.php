<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\GitUser;
use App\Model\GitRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
#[AsCommand(name: 'app:gitlab-members', description: 'Gitlab Members')]
class GitCommand extends Command
{
    public function __construct(
        private readonly GitRepository $gitRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->entityManager;

        $this->truncateTableGitUser();

        $data = $this->gitRepository->getUsersInfoFormatted();

        $this->flushData($data, $em);

        $usersCount = count($data);

        $this->showData($output, $data, $usersCount);

        return Command::SUCCESS;
    }

    /**
     * For each call, the table is cleaned
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    private function truncateTableGitUser(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('GitUser', true));
    }

    /**
     * Data is inserted into the table
     *
     * @param array $data
     * @param EntityManagerInterface $em
     * @return void
     */
    private function flushData(array $data, EntityManagerInterface $em): void
    {
        foreach ($data as $user) {
            $gitUserEntity = new GitUser();
            $gitUserEntity->setName($user['Name']);
            $gitUserEntity->setGitProject($user['Projects']);
            $gitUserEntity->setGitGroup($user['Groups']);
            $em->persist($gitUserEntity);
        }
        $em->flush();
    }

    /**
     * Display data
     *
     * @param OutputInterface $output
     * @param array $data
     * @param int|null $usersCount
     * @return void
     */
    private function showData(OutputInterface $output, array $data, ?int $usersCount): void
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Groups', 'Projects'])
            ->setColumnMaxWidth(0, 220)
            ->setColumnMaxWidth(1, 220)
            ->setColumnMaxWidth(3, 220)
            ->setVertical()
            ->setRows($data);
        $table->setFooterTitle('TOTAL USERS: ' . $usersCount);
        $table->render();

        $outputStyle = new OutputFormatterStyle('red', '#ff0', ['bold', 'blink']);
        $output->getFormatter()->setStyle('fire', $outputStyle);
        $output->writeln(
            '<fire>You can see the results with the filtering option through this link:' .
            '</> <href=http://localhost:9013/admin> http://localhost:9013/admin </>'
        );
    }
}
