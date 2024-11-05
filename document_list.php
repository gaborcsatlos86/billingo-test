<?php

require_once 'App/Command/DataFilterCommand.php';
require_once 'App/Service/DataPrinterService.php';
require_once 'App/Service/DataProcessorService.php';

use App\Command\DataFilterCommand;
use App\Service\DataPrinterService;
use App\Service\DataProcessorService;

if ($argc != 4) {
    echo 'Ambiguous number of parameters!';
    exit(1);
}

$app = new DataFilterCommand(new DataPrinterService(), new DataProcessorService(), filterDocumentType: $argv[1], filterParnerId: $argv[2], filterTotalMinimum: $argv[3]);
$app->runCommand();
