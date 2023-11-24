<?php

namespace Brix\Coder;

use Brix\Coder\Manager\CoderManager;
use Brix\Coder\Manager\DocumentorManager;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\CLIntputHandler;

class Documentation extends AbstractBrixCommand
{

    public DocumentorManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->manager = new DocumentorManager($this->brixEnv);
    }

    public function update(array $argv) {
        $filename = $argv[0];

        $file = phore_file($filename)->assertFile();
        $origContent = $file->get_contents();


        $examples = $this->brixEnv->rootDir->withRelativePath("examples")->assertDirectory();
        $readme = $examples->withRelativePath("README.md")->assertFile();
        $this->manager->createDocumentation($readme, $examples);

        if ( ! In::AskBool("Keep changes?", true)) {
            $file->set_contents($origContent);
        }
    }

}
