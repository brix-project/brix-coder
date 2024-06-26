<?php

namespace Brix\Coder\Manager\Type\Chunk;

class T_ChangeRequestResult_File
{

    /**
     * Provide all changed chunks as array in form "chunk index" => "new chunk content".
     *
     * The chunks returned will later be applied to the original file contents and overwrite the orginal content provided as this index. So you have
     * to make sure the content of changed chunks fits into the rest of the file content!
     *
     * - USE ONLY existing chunk indexes as keys.
     * - TO REMOVE CHUNKS: Set the chunk content to empty string "".
     * - TO ADD NEW LINES: Append or prepend new lines to the end of existing chunks! Do not just replace chunk content with new lines! You must append or prepend the existing content of changed chunks!
     * - YOU HAVE TO make sure the updated chunks fit into the original file content.
     * - YOU MUST omit chunks ids if the content remains unchanged.
     *
     *
     *
     * @var array<string, string>
     */
    public array $content = [];



    /**
     * The full path and filename of the existing or new file
     * @var string
     */
    public string $path;
}
