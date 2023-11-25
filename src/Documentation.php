<?php

namespace Brix\Coder;

use Brix\Coder\Manager\CoderManager;
use Brix\Coder\Manager\DocumentorManager;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\CLIntputHandler;
use Phore\Cli\Input\In;

class Documentation extends AbstractBrixCommand
{

    public DocumentorManager $manager;
    public function __construct()
    {
        parent::__construct();
        $this->manager = new DocumentorManager($this->brixEnv);
    }

    public function update(array $argv) {
        $readme = $this->brixEnv->rootDir->withRelativePath("README.md")->assertFile();

        $origContent = $readme->get_contents();


        $examples = $this->brixEnv->rootDir->withRelativePath("examples")->assertDirectory();

        $this->manager->updateDocumentation($readme, $examples);

        if ( ! In::AskBool("Keep changes?", true)) {
            $readme->set_contents($origContent);
        }
    }
    public function annotate(array $argv) {
        $filename = $argv[0];
        $readme = $this->brixEnv->rootDir->withRelativePath("README.md")->assertFile();

        $origContent = $readme->get_contents();


        $examples = $this->brixEnv->rootDir->withRelativePath("examples")->assertDirectory();

        $this->manager->annotateDocumentation($readme, $examples);

        if ( ! In::AskBool("Keep changes?", true)) {
            $readme->set_contents($origContent);
        }
    }
    public function ask(array $argv) {
        $filename = $argv[0];
        $readme = $this->brixEnv->rootDir->withRelativePath("README.md")->assertFile();

        $origContent = $readme->get_contents();


        $examples = $this->brixEnv->rootDir->withRelativePath("examples")->assertDirectory();

        $this->manager->askForExamples($readme, $examples);


    }

}
