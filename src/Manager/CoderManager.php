<?php

namespace Brix\Coder\Manager;

use Brix\Core\Type\BrixEnv;

class CoderManager
{

    public function __construct(public BrixEnv $brixEnv)
    {
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
            "code" => phore_file($filename)->get_contents(),
            "prompt" => $prompt
        ], $filename);
    }


}
