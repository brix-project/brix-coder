<?php

namespace Brix\Coder;

use Brix\Coder\Manager\CoderManager;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\CLIntputHandler;

class Coder extends AbstractBrixCommand
{

    public CoderManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->manager = new CoderManager($this->brixEnv);
    }

    public function extend(array $argv) {
        $filename = $argv[0];

        $file = phore_file($filename)->assertFile();

        $cli = new CLIntputHandler();
        $prompt = $cli->askMultiLine("How to extend file '$file'?");

        $this->manager->extend($file, $prompt);

    }

}
