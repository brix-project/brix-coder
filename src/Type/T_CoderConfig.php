<?php

namespace Brix\Coder\Type;

class T_CoderConfig
{

    /**
     * @var string|null
     */
    public string|null $exampleDir;

    /**
     * @var string[]
     */
    public array $include = [];

    /**
     * @var string[]
     */
    public array $exclude = [];

}
