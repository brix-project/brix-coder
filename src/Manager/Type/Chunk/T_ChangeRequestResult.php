<?php

namespace Brix\Coder\Manager\Type\Chunk;

class T_ChangeRequestResult
{


    /**
     * A short summary of the tasks done
     *
     * @var string
     */
    public string $summary;


    /**
     * If you cannot perform the task due to missing information or invalid information provide details
     * about wrong or missing information here. It will be displayed to the user in order to alter the job
     * description.
     *
     * Set to null if no error occured.
     *
     * @var string|null
     */
    public string|null $errorMessage = null;


    /**
     * List of all new and updated files to perform the task
     *
     * @var T_ChangeRequestResult_File[]
     */
    public $files = [];

}
