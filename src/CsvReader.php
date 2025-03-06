<?php declare(strict_types=1);

namespace AlanVdb\Csv;

use InvalidArgumentException;
use RuntimeException;
use ArrayAccess;
use Iterator;

class CsvReader implements ArrayAccess, Iterator
{
    private readonly string $filePath;
    private readonly string $delimiter;
    private readonly bool $hasHeader;
    private $handle = null;
    private array $headers = [];
    private int $position = 0;
    private ?array $currentRow = null;

    public function __construct(string $filePath, string $delimiter = ',', bool $hasHeader = true)
    {
        if (!is_readable($filePath)) {
            throw new RuntimeException("Could not read provided file: $filePath");
        }
        if ($delimiter === '') {
            throw new InvalidArgumentException("No delimiter provided.");
        }
        
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
        $this->hasHeader = $hasHeader;
        $this->openFile();
        $this->next();
    }

    private function openFile(): void
    {
        if ($this->handle) {
            fclose($this->handle);
        }

        $this->handle = fopen($this->filePath, 'r');
        if ($this->hasHeader && $this->handle !== false) {
            $this->headers = fgetcsv($this->handle, 0, $this->delimiter) ?: [];
        }
    }

    public function __destruct()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->currentRow[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->currentRow[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new RuntimeException("CSV data is read-only.");
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException("CSV data is read-only.");
    }

    public function current(): mixed
    {
        return $this->currentRow;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        if ($this->handle !== false) {
            $data = fgetcsv($this->handle, 0, $this->delimiter);
            if ($data === false) {
                $this->currentRow = null;
            } elseif ($this->hasHeader && count($data) === count($this->headers)) {
                $this->currentRow = array_combine($this->headers, $data);
            } else {
                $this->currentRow = $data;
            }
            $this->position++;
        }
    }

    public function rewind(): void
    {
        $this->openFile();
        $this->position = 0;
        $this->next();
    }

    public function valid(): bool
    {
        return $this->currentRow !== null;
    }
}
