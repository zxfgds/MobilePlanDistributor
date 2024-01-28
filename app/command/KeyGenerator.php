<?php

namespace app\command;

use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class KeyGenerator extends Command
{
    protected static $defaultName        = 'key:generate';
    protected static $defaultDescription = 'KeyGenerator';
    
    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }
    
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $str = Str::random(32);
        putenv('APP_SECRET_KEY', base64_encode($str));
        return self::SUCCESS;
    }
    
}
