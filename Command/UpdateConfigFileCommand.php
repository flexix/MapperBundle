<?php

namespace Flexix\MapperBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Flexix\MapperBundle\Generator\Mapper;

class UpdateConfigFileCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('flexix:class-mapper:update-config-file')
                ->setDescription('Updates config file');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $classMapperFile = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'mapper.yml';
        if (!file_exists($classMapperFile)) {
            fopen($classMapperFile, "w");
        }
        $classMapper = new Mapper($classMapperFile, $manager);
        $classMapper->updateConfigFile();
         $output->writeln('<info>Remember to add  "- { resource: mapper.yml }" to import section</info>');     
        
        
    }

}
