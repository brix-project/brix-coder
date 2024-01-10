<?php

namespace Brix\Coder\Manager;

use Brix\Coder\Helper\FileLoader;
use Brix\Coder\Manager\Type\T_ChangeRequestResult;
use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\Type\BrixEnv;
use Lack\OpenAi\Helper\JsonSchemaGenerator;

use Phore\Cli\Output\Out;

class ChangeRequestManager
{
    private T_CoderConfig $config;

    public function __construct(public BrixEnv $brixEnv)
    {
        $this->config = $brixEnv->brixConfig->get("coder", T_CoderConfig::class, file_get_contents(__DIR__ . "/../config_tpl.yml"));


    }



    public function createChangeRequest($jobDescription)
    {
        $jobDir = $this->brixEnv->rootDir->withRelativePath(".brix-job")->assertDirectory(true);
        $jobDir->withRelativePath("job.md")->asFile()->set_contents($jobDescription);

    }


    public function performTasks()
    {
        $jobRoot = $this->brixEnv->rootDir->withRelativePath(".brix-job");
        if ( ! $jobRoot->isDirectory())
            throw new \InvalidArgumentException("Job not found: " . $jobRoot);

       // $jobDescription = $jobRoot->withFileName("job.md")->assertFile()->get_contents();


        $fileLoader = new FileLoader($this->brixEnv->rootDir, $this->config->include, $this->config->exclude);


        $files = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt_cr/prompt_cr.txt", [
            "fileData" => phore_json_encode($fileLoader->generateFileContent()),
          //  "jobDescription" => $jobDescription
        ], T_ChangeRequestResult::class, true);




    }


}
