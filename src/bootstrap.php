<?php


namespace Brix;




use Brix\Coder\Coder;
use Brix\Coder\CR;
use Brix\Coder\Doc;
use Brix\Core\Type\BrixEnv;
use Phore\Cli\CliDispatcher;


CliDispatcher::addClass(Coder::class);
CliDispatcher::addClass(Doc::class);
CliDispatcher::addClass(CR::class);
