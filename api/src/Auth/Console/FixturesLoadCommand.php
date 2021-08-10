<?php

declare(strict_types=1);

namespace App\Auth\Console;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixturesLoadCommand
 * Config for load fixtures
 */
class FixturesLoadCommand extends Command
{
    private EntityManagerInterface $em;

    /**
     * @var string[]
     */
    private array $paths;


    /**
     * FixturesLoadCommand constructor.
     *
     * @param EntityManagerInterface $em
     * @param string[]               $paths
     */
    public function __construct(EntityManagerInterface $em, array $paths)
    {
        parent::__construct();
        $this->em = $em;
        $this->paths = $paths;
    }

    /**
     * Config for current fixtures
     * Access by set name
     */
    protected function configure(): void
    {
        $this
            ->setName('fixtures:load')
            ->setDescription('Load fixtures')
        ;
    }

    /**
     * Execute fixtures load
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<comment>Loading fixtures</comment>');

        $loader = new Loader();

        foreach ($this->paths as $path) {
            $loader->loadFromDirectory($path);
        }

        $executor = new ORMExecutor($this->em, new ORMPurger());

        /**
         * Custom logger for run fixtures process
         * */
        $executor->setLogger(
            static function (string $message) use ($output) {
                $output->writeln($message);
            }
        );

        /**
         *  Add param 'true' in 'execute' if need append data in db
         *  By default first clear db, then insert new data
         */
        $executor->execute($loader->getFixtures());

        $output->writeln('<info>Done</info>');

        return 0;
    }
}
