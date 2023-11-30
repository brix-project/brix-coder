<?php

namespace Brix\Coder;

use Brix\Coder\Manager\CoderManager;
use Brix\Coder\Manager\DocumentorManager;
use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\AbstractBrixCommand;
use Phore\Cli\CLIntputHandler;
use Phore\Cli\Input\In;

class Doc extends AbstractBrixCommand
{

    public DocumentorManager $manager;
    
    public function __construct()
    {
        parent::__construct();
        $this->brixEnv->brixConfig->get("coder", T_CoderConfig::class, file_get_contents(__DIR__ . "/config_tpl.yml"));

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
    public function create_update_readme(array $argv) {
        $filename = phore_file($argv[0]);
        

        if ( ! $filename->exists()) {
            $job = In::AskMultiLine("New Readme: What should i do?");
            $this->manager->createReadme($filename, $job);

        } else {
            $origContent = $filename->get_contents();
            $job = In::AskMultiLine("Existing Readme: What should i edit?");
            $this->manager->updateReadme($filename, $job);
        }


        if ( ! In::AskBool("Keep changes?", true)) {
            $filename->set_contents($origContent);
        }
    }
    
    public function create_update_fragment(array $argv) {
        $filename = phore_file($argv[0]);
        

        if ( ! $filename->exists()) {
            $job = In::AskMultiLine("New Fragment: What should i do?");
            $this->manager->createFragment($filename, $job);

        } else {
            $origContent = $filename->get_contents();
            $job = In::AskMultiLine("Existing Fragment: What should i edit?");
            $this->manager->updateFragment($filename, $job);
        }


        if ( ! In::AskBool("Keep changes?", true)) {
            $filename->set_contents($origContent);
        }
    }
    
    public function optimize_example_file(array $argv) {
        $file = phore_file($argv[0])->assertFile();
        $origContent = $file->get_contents();

        $this->manager->optimizeExample($file);

        if ( ! In::AskBool("Keep changes?", true)) {
            $file->set_contents($origContent);
        }
    }

}
