<?php

declare(strict_types=1);

namespace App\Service;

require_once 'AbstractService.php';

class DataProcessorService extends AbstractService
{
    private ?array $dataHeader = null;
    
    private array $dataSource;
    
    private bool $initSuccessfull;
    
    public function __construct(
        private string $fileName = 'document_list.csv',
    ) {
        $this->initSuccessfull = $this->init();
    }
    
    public function isInitSuccessFull(): bool
    {
        return $this->initSuccessfull;
    }
    
    public function getDataHeader(): ?array
    {
        return $this->dataHeader;
    }
    
    public function getDataSource(): array
    {
        return $this->dataSource;
    }
    
    public function init(): bool
    {
        if (empty($this->fileName)) {
            $this->addError('No file name!');
            return false;
        }
        if (($handler = fopen($this->fileName, 'r')) == false) {
            $this->addError('Error on file opening: '. $this->fileName);
            return false;
        }
        
        $this->handleCsv($handler);
        fclose($handler);
        
        return true;
    }
    
    protected function handleCsv($handler, bool $firstLineIsHeader = true)
    {
        while (($data = fgetcsv($handler, null, ';')) !== false) {
            if ($firstLineIsHeader && $this->dataHeader == null) {
                $this->dataHeader = $data;
                continue;
            }
            $source = [];
            for ($i = 0; $i < count($data); $i++) {
                $value = json_decode($data[$i]);
                if (json_last_error() == 0) {
                    $source[$this->dataHeader[$i]] = (is_object($value) ? (array)$value : $value);
                } else {
                    $source[$this->dataHeader[$i]] = $data[$i];
                }
            }
            $this->dataSource[] = $source;
        }
    }
    
}
