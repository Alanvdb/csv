# CsvReader Class

A simple and efficient PHP class for reading CSV files. This class implements the `ArrayAccess` and `Iterator` interfaces to provide a flexible way to interact with CSV data.

## Features

- Reads CSV files with a customizable delimiter (default is a comma).
- Supports CSV files with or without a header row.
- Provides access to CSV rows as arrays or associative arrays (if a header is provided).
- Implements `ArrayAccess` for easy array-like access to CSV rows.
- Implements `Iterator` for easy iteration over CSV rows.
- Read-only access to CSV data (modifying the data is not allowed).

## Installation

Simply download the `CsvReader.php` file and include it in your project. Alternatively, you can install it via Composer.

## Usage

### Example 1: Simple CSV with Header

```php
use AlanVdb\Csv\CsvReader;

$csv = new CsvReader('path/to/your/file.csv');

foreach ($csv as $row) {
    echo $row['ColumnName']; // Access columns by header name if available
}
```

### Example 2: Simple CSV without Header

```php
use AlanVdb\Csv\CsvReader;

$csv = new CsvReader('path/to/your/file.csv', ',', false);

foreach ($csv as $row) {
    print_r($row); // Access each row as a simple indexed array
}
```

### Example 3: Accessing Data via ArrayAccess

```php
use AlanVdb\Csv\CsvReader;

$csv = new CsvReader('path/to/your/file.csv');

foreach ($csv as $row) {
    echo $row[0]; // Access the first column of the row
}
```

## Methods

### `__construct(string $filePath, string $delimiter = ',', bool $hasHeader = true)`

- `filePath` (string): Path to the CSV file.
- `delimiter` (string): The delimiter used in the CSV file (default is `,`).
- `hasHeader` (bool): Whether the CSV file has a header row (default is `true`).

Throws `RuntimeException` if the file cannot be read and `InvalidArgumentException` if the delimiter is empty.

### `current() : mixed`

Returns the current row of the CSV as an associative array (if the file has a header) or a simple indexed array.

### `key() : int`

Returns the current row's position (index) in the CSV.

### `next() : void`

Moves the pointer to the next row in the CSV file.

### `rewind() : void`

Rewinds the file pointer to the beginning of the CSV file and prepares the reader to start from the first row.

### `valid() : bool`

Checks if the current row is valid (i.e., not the end of the file).

### `offsetExists(mixed $offset) : bool`

Checks if a column (offset) exists in the current row.

### `offsetGet(mixed $offset) : mixed`

Gets the value of a specific column in the current row.

### `offsetSet(mixed $offset, mixed $value) : void`

Throws a `RuntimeException` since the CSV data is read-only.

### `offsetUnset(mixed $offset) : void`

Throws a `RuntimeException` since the CSV data is read-only.

## Requirements

- PHP 7.4 or higher.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.