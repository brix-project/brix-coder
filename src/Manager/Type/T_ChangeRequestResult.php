<?php

namespace Brix\Coder\Manager\Type;

class T_ChangeRequestResult
{


    /**
     * A short summary of the tasks done
     *
     * @var string
     */
    public string $summary;

    /**
     * List of all new and updated files to perform the task
     *
     * @var T_ChangeRequestResult_File[]
     */
    public $files = [];

}
