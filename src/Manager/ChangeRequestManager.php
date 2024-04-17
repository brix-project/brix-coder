<?php

namespace Brix\Coder\Manager;

use Brix\Coder\Helper\ChunkReaderWriter;
use Brix\Coder\Helper\ExampleLoader;
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

    private function performUpdateTask($fileLoader, $exampleLoader) {
        $files = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt_cr/prompt_cr.txt", [
            "fileData" => $fileLoader->generateFileContent(),
            "exampleData" => $exampleLoader->generateFileContent(),
            //  "jobDescription" => $jobDescription
        ], T_ChangeRequestResult::class, true);


        $rootDir = $this->brixEnv->rootDir;
        $i = 0;
        foreach ($files->files as $fileCr) {
            $i++;


            echo "File: " . $rootDir->withRelativePath($fileCr->path) . "\n";
            $file = $rootDir->withRelativePath($fileCr->path)->asFile()->createPath();
            $file->set_contents(ChunkReaderWriter::ChunkUpdateFile($file->exists() ? $file->get_contents() : "", $fileCr->content));
        }
    }

    private function performChukTask($fileLoader, $exampleLoader) {
        $files = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt_cr/prompt_cr.txt", [
            "fileData" => $fileLoader->generateFileContent(),
            "exampleData" => $exampleLoader->generateFileContent(),
            //  "jobDescription" => $jobDescription
        ], \Brix\Coder\Manager\Type\Chunk\T_ChangeRequestResult::class, true);


        $rootDir = $this->brixEnv->rootDir;
        $i = 0;
        foreach ($files->files as $fileCr) {
            $i++;


            echo "File: " . $rootDir->withRelativePath($fileCr->path) . "\n";
            $file = $rootDir->withRelativePath($fileCr->path)->asFile()->createPath();
            $file->set_contents(ChunkReaderWriter::ChunkUpdateFile($file->exists() ? $file->get_contents() : "", $fileCr->content));
        }
    }

    private function performFullFileTask($fileLoader, $exampleLoader) {
        $files = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt_cr/prompt_cr.txt", [
            "fileData" => $fileLoader->generateFileContent(),
            "exampleData" => $exampleLoader->generateFileContent(),
            //  "jobDescription" => $jobDescription
        ], \Brix\Coder\Manager\Type\FullFile\T_ChangeRequestResult::class, true);


        $rootDir = $this->brixEnv->rootDir;
        $i = 0;
        foreach ($files->files as $fileCr) {
            $i++;


            echo "File: " . $rootDir->withRelativePath($fileCr->path) . "\n";
            $file = $rootDir->withRelativePath($fileCr->path)->asFile()->createPath();
            $file->set_contents($fileCr->content);
        }
    }
    public function performTasks($chunkMode = false)
    {
        $jobRoot = $this->brixEnv->rootDir->withRelativePath(".brix-job");
        if ( ! $jobRoot->isDirectory())
            throw new \InvalidArgumentException("Job not found: " . $jobRoot);

       // $jobDescription = $jobRoot->withFileName("job.md")->assertFile()->get_contents();


        $fileLoader = new FileLoader($this->brixEnv->rootDir, $this->config->include, $this->config->exclude);

        $exampleLoader = new ExampleLoader($this->brixEnv->rootDir, ["./node_modules/", "./vendor/"]);



        if ($chunkMode) {
            $this->performChukTask($fileLoader, $exampleLoader);
        } else {
            $this->performFullFileTask($fileLoader, $exampleLoader);
        }




    }


}
