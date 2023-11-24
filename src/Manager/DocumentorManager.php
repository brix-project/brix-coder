<?php

namespace Brix\Coder\Manager;

use Brix\Core\Type\BrixEnv;

class DocumentorManager
{

    public function __construct(public BrixEnv $brixEnv)
    {
    }


    protected function generateExampleString(string $exampleDir) : string {
        $example = "";
        foreach (phore_dir($exampleDir)->listFiles() as $file) {
            $example .= "\n\n// Example: " . $file->getUri() . "\n";
            $example .= "\"\"\"\n";
            $example .= $file->get_contents() . "\n\"\"\"\n";
        }
        return $example;
    }


    public function createDocumentation(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-generate-documentation.txt", [
            "examples" => $this->generateExampleString($exampleDir),
            "original" => $filename->get_contents()
        ], $filename);
    }

}
