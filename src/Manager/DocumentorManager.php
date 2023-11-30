<?php

namespace Brix\Coder\Manager;

use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\Type\BrixEnv;
use Phore\Cli\Input\In;

class DocumentorManager
{

    private T_CoderConfig $config;

    public function __construct(public BrixEnv $brixEnv)
    {
        $this->config = $brixEnv->brixConfig->get("coder", T_CoderConfig::class);
    }


    protected function generateExampleString() : string {
        // Load contents of .gitignore
        $gitIgnore = phore_file($this->brixEnv->rootDir . "/.gitignore")->get_contents_array();
        $exampleDir = $this->brixEnv->rootDir;
        $example = "";
        foreach ($this->config->include as $include) {
            foreach (phore_dir($exampleDir . "/". $include)->listFiles(null, true) as $file) {
                // Skip files that are ignored by .gitignore
                foreach ($gitIgnore as $line) {
                    if($line == "") continue;
                    if (str_starts_with($line, "#")) continue;
                    if (str_starts_with($line, "!")) continue;
                    if (str_starts_with($line, "/")) $line = substr($line, 1);

                    if (fnmatch($line, $file->getRelPath())) {
                        echo "\n\nSkipping: " . $file->getRelPath() . "\n";
                        continue 2;
                    }


                }

                $incUri = phore_uri("/" . $include . "/". $file->getRelPath())->clean();
                echo "\nIncluding: " . $incUri . "";
                $example .= "\n\nFile: /" .  $incUri . "\n";
                $example .= "\"\"\"\n";
                $example .= $file->get_contents() . "\n\"\"\"\n";
            }

        }
        return $example;
    }


    public function updateDocumentation(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-update-documentation.txt", [
            "examples" => $this->generateExampleString(),
            "original" => $filename->get_contents()
        ], $filename, true);

    }

     public function annotateDocumentation(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-annotate-documentation.txt", [
            "examples" => $this->generateExampleString(),
            "original" => $filename->get_contents()
        ], $filename);
    }
 
    public function createReadme($filename, $nameAndPurpose) {
        $filename = phore_file($filename);

        $data = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt/prompt-create-readme.txt", [
            "examples" => $this->generateExampleString(),
            "nameAndPurpose" => $nameAndPurpose
        ]);   
        $filename->set_contents($data);
    }
    
    public function updateReadme(string $filename) {
        $filename = phore_file($filename)->assertFile();

        $data = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt/prompt-update-readme.txt", [
            "examples" => $this->generateExampleString(),
            "content" => $filename->get_contents(),
        ]);
        $filename->set_contents($data);
    }
    
    
    
    
    
    public function createFragment($filename, $job) {
        $filename = phore_file($filename);

        $data = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt/prompt-create-fragment.txt", [
            "examples" => $this->generateExampleString(),
            "input" => $job
        ]);   
        $filename->set_contents($data);
    }
    
    public function updateFragment(string $filename, string $job) {
        $filename = phore_file($filename)->assertFile();

        $data = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt/prompt-update-fragment.txt", [
            "examples" => $this->generateExampleString(),
            "filename" => $filename->getBasename(),
            "content" => $filename->get_contents(),
            "job" => $job
        ]);   
        $filename->set_contents($data);
    }
    
    /**
     * Optimize the example file
     * 
     * @param string $filename
     * @return void
     * @throws \Phore\FileSystem\Exception\FileAccessException
     * @throws \Phore\FileSystem\Exception\FileNotFoundException
     * @throws \Phore\FileSystem\Exception\FilesystemException
     */
    public function optimizeExample(string $filename) {
        $filename = phore_file($filename);
        $filename->touch();

        $data = $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt/prompt-optimize-example.txt", [
            "examples" => $this->generateExampleString($exampleDir),
            "filename" => $filename->getBasename(),
            "input" => $filename->get_contents(), //$filename->get_contents()
        ]);
        $filename->set_contents($data);
    }


}
