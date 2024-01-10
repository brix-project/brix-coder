<?php

namespace Brix\Coder\Manager\Type;

class T_ChangeRequestResult_File
{

    /**
     * The full path and filename of the existing or new file
     * @var string
     */
    public string $filename;



    /**
     * If the file is updated: Provide the patch as unified diff
     *
     * @var string|null
     */
    public string|null $patch;

    /**
     * If the file is new: Provide the content of the file
     *
     * @var string|null
     */
    public string|null $content;
}