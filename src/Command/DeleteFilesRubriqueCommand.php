<?php

namespace App\Command;

use App\Service\DeleteRubriqueFiles;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * DeleteFilesRubriqueCommand
 *
 * Command to delete files on tory rubrique files
 */
class DeleteFilesRubriqueCommand extends Command
{
    protected static $defaultName = 'app:delete:rubfiles';
    protected static $defaultDescription = 'Delete files on directory rubfiles';

    private $deleteRubriqueFiles;

    /**
     * @param DeleteRubriqueFiles $deleteRubriqueFiles
     * @param string|null         $name
     */
    public function __construct(DeleteRubriqueFiles $deleteRubriqueFiles, string $name = null)
    {
        $this->deleteRubriqueFiles = $deleteRubriqueFiles;
        parent::__construct($name);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            '<comment>Delete files</comment>',
            '<comment>============</comment>',
            '',
        ]);

        if (!$this->deleteRubriqueFiles->remove()) {
            $output->writeln('<error>No files deleted</error>');

            return Command::FAILURE;
        }

        $output->writeln('<info>Files deleted successfully</info>');

        return Command::SUCCESS;
    }
}
