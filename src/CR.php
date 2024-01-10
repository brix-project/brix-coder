<?php

namespace Brix\Coder;

use Brix\Coder\Manager\ChangeRequestManager;
use Brix\Coder\Manager\CoderManager;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\Input\In;

class CR extends AbstractBrixCommand
{
    public ChangeRequestManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->manager = new ChangeRequestManager($this->brixEnv);
    }


    public function create(array $argv) {
        $jobDesc = implode (" ", $argv);

        if ($jobDesc === null) {
            $jobDesc = In::AskMultiLine("What is the purpose of this change request?");
        }

        $this->manager->createChangeRequest($jobDesc);

    }

    public function perform(array $argv) {
        $this->manager->performTasks();
    }


}
