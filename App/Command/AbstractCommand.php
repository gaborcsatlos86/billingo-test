<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\DataPrinterService;

abstract class AbstractCommand
{
    public function __construct(
        protected DataPrinterService $dataPrinter
    ) { }
    
    abstract public function runCommand(array $params = []): bool;
    
    protected function print(string $message = ''): void
    {
        $this->dataPrinter->printText($message);
    }
    
    protected function printNL(string $message = ''): void
    {
        $this->dataPrinter->printTextNL($message);
    }
}
