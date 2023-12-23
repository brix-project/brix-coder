<?php

namespace Brix\Coder\Manager;

use Brix\Coder\Type\T_CoderConfig;
use Brix\Core\Type\BrixEnv;

class CoderManager
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

    /**
     * Extends functionality of a existing file
     *
     * @param string $filename
     * @param string $prompt
     * @return void
     */
    public function extend(string $filename, string $prompt) {
        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-extend.txt", [
            "files" => $this->generateExampleString(),
            "editfile" => $filename,
            "code" => phore_file($filename)->get_contents(),
            "prompt" => $prompt
        ], $filename, noAppend: true);
    }


}
