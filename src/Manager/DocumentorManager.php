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
        foreach (phore_dir($exampleDir)->listFiles(null, true) as $file) {
            $example .= "\n\nFile: /" . $file->getRelPath() . "\n";
            $example .= "\"\"\"\n";
            $example .= $file->get_contents() . "\n\"\"\"\n";
        }
        return $example;
    }


    public function updateDocumentation(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-update-documentation.txt", [
            "examples" => $this->generateExampleString($exampleDir) . "\n" . $this->generateExampleString("src"),
            "original" => $filename->get_contents()
        ], $filename);

    }

     public function annotateDocumentation(string $filename, string $exampleDir) {
        $filename = phore_file($filename);
        $filename->touch();

        $this->brixEnv->getOpenAiQuickFacet()->promptStreamToFile(__DIR__ . "/prompt/prompt-annotate-documentation.txt", [
            "examples" => $this->generateExampleString($exampleDir) . "\n" . $this->generateExampleString("src"),
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
