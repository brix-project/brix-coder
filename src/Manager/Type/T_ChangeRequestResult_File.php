<?php

namespace Brix\Coder\Manager\Type;

class T_ChangeRequestResult_File
{

    /**
     * The full path and filename of the existing or new file
     * @var string
     */
    public string $path;

    

    /**
     * Provide the full new content of the file
     * 
     * - YOU MUST preserve lines and logic and text that is unaffected by the change
     * - YOU MUST output the full file content
     *
     * @var string|null
     */
    public string|null $content = null;
}
