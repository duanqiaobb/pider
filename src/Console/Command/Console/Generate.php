<?php
namespace Pider\Console\Command\Console;

/**
 * Define `pider` command under console
 */
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command {

    public $isExec = True;

    protected function configure() {
        $this->setName('generate')
            ->setDescription("generate executable files for command defined")
            ->setHelp("This command allows generate executable file to src/bin directory");
    }
    protected function execute(InputInterface $input, OutputInterface $output) {
    }
}

