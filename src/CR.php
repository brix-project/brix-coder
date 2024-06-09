<?php

namespace Brix\Coder;

use Brix\Coder\Manager\ChangeRequestManager;
use Brix\Coder\Manager\CoderManager;
use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\Input\In;

class CR extends AbstractBrixCommand
{
    public ChangeRequestManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->brixEnv->brixConfig->get("coder", T_CoderConfig::class, file_get_contents(__DIR__ . "/config_tpl.yml"));
        $this->manager = new ChangeRequestManager($this->brixEnv);
    }


    public function ask(array $argv)
    {
        $questiong = implode(" ", $argv);
        echo $this->manager->askQuestion($questiong);

    }

    public function create(array $argv) {
        $jobDesc = implode (" ", $argv);

        if ($jobDesc === null) {
            $jobDesc = In::AskMultiLine("What is the purpose of this change request?");
        }

        $this->manager->createChangeRequest($jobDesc);

    }

    public function perform(array $argv, bool $chunk = false) {
        $this->manager->performTasks($chunk);
    }


}
