<?php

declare(strict_types=1);

namespace App\Command;

require_once 'AbstractCommand.php';

use App\Service\{DataPrinterService, DataProcessorService};


class DataFilterCommand extends AbstractCommand
{
    private array $tableHeader = [
        'document_id',
        'document_type',
        'partner name',
        'total'
    ];
    
    public function __construct(
        DataPrinterService $dataPrinter,
        protected DataProcessorService $dataProcessor,
        protected $filterDocumentType,
        protected $filterParnerId,
        protected $filterTotalMinimum
    ) {
        parent::__construct($dataPrinter);
    }
    
    public function runCommand(array $params = []): bool
    {
        if (!$this->dataProcessor->isInitSuccessFull()) {
            $this->dataPrinter->printTextNL('Error on init data process!');
            foreach ($this->dataProcessor->getErrors() as $error) {
                $this->dataPrinter->printTextNL($error);
            }
            return false;
        }
        foreach ($this->tableHeader as $headInfo) {
            $this->dataPrinter->printText(str_pad($headInfo, 20));
        }
        $this->dataPrinter->printTextNL();
        $this->dataPrinter->printTextNL((str_repeat('=', 20 * (count($this->tableHeader)))));
        if (!is_string($this->filterDocumentType) || !is_numeric($this->filterParnerId) || !is_numeric($this->filterTotalMinimum)) {
            return false;
        }
        
        foreach ($this->filterDocuments() as $item) {
            $total = 0;
            foreach ($item['items'] as $documentItem) {
                $total += ($documentItem->unit_price * $documentItem->quantity);
            }
            if ($total > $this->filterTotalMinimum) {
                $this->dataPrinter->printTextNL(str_pad((string)$item['id'], 20) . str_pad($item['document_type'], 20). str_pad($item['partner']['name'], 20). str_pad((string)$total, 20));
            }
        }
        
        return true;
    }
    
    protected function filterDocuments(): array
    {
        $toFilter = $this->dataProcessor->getDataSource();
        foreach ($toFilter as $key => $item) {
            if (
                ($item['document_type'] != $this->filterDocumentType) ||
                (!isset($item['partner']['id']) || empty($item['partner']['id']) || $item['partner']['id'] != $this->filterParnerId))
            {
                unset($toFilter[$key]);
            }
        }
        return array_values($toFilter);
    }
    
}
