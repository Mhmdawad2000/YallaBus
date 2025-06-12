<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseFilter
{
    protected string $modelClass;
    protected array $filters;
    protected array $searchableTypes = [
        'string',
        'varchar',
        'text',
        'char',
        'longtext',
        'mediumtext',
        'tinytext',
        'enum',
        'ntext',
        'nvarchar'
    ];
    protected array $numericTypes = [
        'int',
        'integer',
        'bigint',
        'smallint',
        'float',
        'double',
        'decimal',
        'real',
        'tinyint',
        'mediumint',
        'numeric'
    ];
    protected array $dateTypes = [
        'date',
        'datetime',
        'timestamp',
        'timestamptz',
        'time',
        'year'
    ];
    protected array $booleanTypes = [
        'boolean',
        'bool',
        'tinyint(1)',
        'bit',
        'smallint(1)',
        'int(1)'
    ];
    public function __construct(string $modelClass, Request $request)
    {
        $this->modelClass = $modelClass;
        $this->filters = $request->except('role');
    }

    public function execute(): Builder
    {
        $model = new $this->modelClass;
        $table = $model->getTable();
        $columns = Schema::getColumnListing($table);
        $columnTypes = $this->getColumnTypes($table, $columns);
        $query = $model->newQuery();

        if (isset($this->filters['search']) && !empty($this->filters['search'])) {
            $searchTerm = $this->filters['search'];
            unset($this->filters['search']);
            $query->where(function ($q) use ($columns, $columnTypes, $searchTerm) {
                foreach ($columns as $column) {
                    if (in_array($columnTypes[$column], $this->searchableTypes)) {
                        $q->orWhere($column, 'like', '%' . $searchTerm . '%');
                    }
                }
            });
        }

        foreach ($this->filters as $field => $value) {
            if (!in_array($field, $columns)) {
                continue;
            }
            $type = $columnTypes[$field];
            if ($value === "null") {
                $query->whereNull($field);
                continue;
            }
            if ($value === "notNull") {
                $query->whereNotNull($field);
                continue;
            }
            if (in_array($type, $this->booleanTypes)) {
                $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($boolValue !== null) {
                    $query->where($field, $boolValue);
                }
                continue;
            }
            if ($this->isNumericType($type)) {
                $query->where($field, $value);
                continue;
            }
            if ($this->isDateType($type) && str_contains($value, ' to ')) {
                [$start, $end] = explode(' to ', $value);
                $query->whereBetween($field, [trim($start), trim($end)]);
                continue;
            }
            $query->where($field, 'like', '%' . $value . '%');
        }

        return $query;
    }

    protected function getColumnTypes(string $table, array $columns): array
    {
        $types = [];
        foreach ($columns as $column) {
            $types[$column] = Schema::getColumnType($table, $column);
        }
        return $types;
    }

    protected function isNumericType(string $type): bool
    {
        return in_array($type, $this->numericTypes);
    }

    protected function isDateType(string $type): bool
    {
        return in_array($type, $this->dateTypes);
    }
}