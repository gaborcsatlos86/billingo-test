<?php

declare(strict_types=1);

namespace App\Service;

require_once 'AbstractService.php';

class DataPrinterService extends AbstractService
{
    public function printText(string $text = ''): void
    {
        echo $text;
    }
    
    public function printTextNL(string $text = ''): void
    {
        $this->printText($text. PHP_EOL);
    }
}
