<?php

namespace Brix\Coder\Manager;

use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\Type\BrixEnv;

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
        ], $filename);

    }

     public function annotateDocumentation(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-annotate-documentation.txt", [
            "examples" => $this->generateExampleString(),
            "original" => $filename->get_contents()
        ], $filename);
    }

    public function askForExamples(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptData(__DIR__ . "/prompt/prompt-ask-for-examples.txt", [
            "examples" => $this->generateExampleString($exampleDir). "\n" . $this->generateExampleString("src"),
            "original" => "", //$filename->get_contents()
        ]);
    }


}
