<?php

declare(strict_types=1);

namespace App\Service;

abstract class AbstractService
{
    protected array $errors = [];
    
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    public function addError(string $errorMsg): self
    {
        $this->errors[] = $errorMsg;
        
        return $this;
    }
}
