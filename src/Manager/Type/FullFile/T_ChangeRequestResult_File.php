<?php

namespace Brix\Coder\Manager\Type\FullFile;

class T_ChangeRequestResult_File
{

    /**
     * Provide the updated full file content as string
     *
     * It will overwrite the original file.
     *
     *
     * @var string
     */
    public string $content;



    /**
     * The full path and filename of the existing or new file
     * @var string
     */
    public string $path;
}
