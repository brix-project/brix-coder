<?php

namespace Brix\Coder;

use Brix\Coder\Manager\CoderManager;
use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\CLIntputHandler;

class Coder extends AbstractBrixCommand
{

    public CoderManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->brixEnv->brixConfig->get("coder", T_CoderConfig::class, file_get_contents(__DIR__ . "/config_tpl.yml"));
        $this->manager = new CoderManager($this->brixEnv);
    }

    public function extend(array $argv) {
        $filename = $argv[0];

        $file = phore_file($filename)->assertFile();
        $origContent = $file->get_contents();

        $cli = new CLIntputHandler();
        $prompt = $cli->askMultiLine("How to extend file '$file'?");

        $this->manager->extend($file, $prompt);

        if ( ! $cli->askBool("Keep changes?", true)) {
            $file->set_contents($origContent);
        }
    }

}
