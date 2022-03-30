<?php

require 'vendor/autoload.php';

use KristiinaMelissa\SpinTekPraktika\Table;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new Table('tabel'));

$application->run();