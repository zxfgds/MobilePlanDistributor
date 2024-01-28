<?php

namespace app\command;

use app\library\DatabaseHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class DatabaseTool extends Command
{
    protected static $defaultName        = 'db:tool';
    protected static $defaultDescription = 'DatabaseTool';
    
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('action', InputArgument::OPTIONAL, 'Name description');
    }
    
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('action');
        
        $filePath = databasePath('sql/' . env("APP_NAME") . '.sql');
        if ($name == 'import') {
            if (!file_exists($filePath)) {
                $output->writeln('备份不存在');
                exit;
            }
            DatabaseHelper::import($filePath);
        }
        
        if ($name == 'export') {
            DatabaseHelper::backup();
        }
        $output->writeln($name);
        return self::SUCCESS;
    }
    
}
