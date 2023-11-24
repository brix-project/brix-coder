<?php


namespace Brix;




use Brix\Coder\Coder;
use Brix\Coder\Documentation;
use Phore\Cli\CliDispatcher;

CliDispatcher::addClass(Coder::class);
CliDispatcher::addClass(Documentation::class);
