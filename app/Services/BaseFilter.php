<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseFilter
 *
 * This class provides dynamic filtering capabilities based on incoming request parameters.
 * It handles search, null/notNull checks, booleans, numerics, dates, and basic text matches.
 */
class BaseFilter
{
    /**
     * The model class to filter.
     *
     * @var string
     */
    protected string $modelClass;

    /**
     * The filters extracted from the request.
     *
     * @var array
     */
    protected array $filters;

    /**
     * Data types considered searchable via LIKE.
     *
     * @var array
     */
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

    /**
     * Data types considered numeric.
     *
     * @var array
     */
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

    /**
     * Data types considered date or datetime.
     *
     * @var array
     */
    protected array $dateTypes = [
        'date',
        'datetime',
        'timestamp',
        'timestamptz',
        'time',
        'year'
    ];

    /**
     * Data types considered boolean.
     *
     * @var array
     */
    protected array $booleanTypes = [
        'boolean',
        'bool',
        'tinyint(1)',
        'bit',
        'smallint(1)',
        'int(1)'
    ];

    /**
     * BaseFilter constructor.
     *
     * @param string $modelClass
     * @param Request $request
     */
    public function __construct(string $modelClass, Request $request)
    {
        $this->modelClass = $modelClass;

        // Extract filters from the request all
        $this->filters = $request->all();

    }

    /**
     * Apply filters and return a query builder.
     *
     * @return Builder
     */
    public function execute(): Builder
    {
        $model = new $this->modelClass;
        $table = $model->getTable();

        // Get column names and types
        $columns = Schema::getColumnListing($table);
        $columnTypes = $this->getColumnTypes($table, $columns);

        // Initialize a new query
        $query = $model->newQuery();

        // Global search feature on all searchable fields
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

        // Loop through each filter field and apply appropriate filtering
        foreach ($this->filters as $field => $value) {
            if (!in_array($field, $columns)) {
                continue;
            }

            $type = $columnTypes[$field];

            // Check for null / notNull
            if ($value === "null") {
                $query->whereNull($field);
                continue;
            }

            if ($value === "notNull") {
                $query->whereNotNull($field);
                continue;
            }

            // Boolean field filtering
            if (in_array($type, $this->booleanTypes)) {
                $boolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($boolValue !== null) {
                    $query->where($field, $boolValue);
                }
                continue;
            }

            // Numeric field filtering
            if ($this->isNumericType($type)) {
                $values = explode(',', $value);
                $query->where(function ($q) use ($field, $values) {
                    foreach ($values as $val) {
                        $val = trim($val);
                        if (str_starts_with($val, '!')) {
                            $q->where($field, '!=', ltrim($val, '!'));
                        } else if (str_starts_with($val, '>')) {
                            $q->where($field, '>', ltrim($val, '>'));
                        } elseif (str_starts_with($val, '<')) {
                            $q->where($field, '<', ltrim($val, '<'));
                        } elseif (str_starts_with($val, '>=')) {
                            $q->where($field, '>=', ltrim($val, '>='));
                        } elseif (str_starts_with($val, '<=')) {
                            $q->where($field, '<=', ltrim($val, '<='));
                        } else {
                            $q->orWhere($field, $val);
                        }
                    }
                });

                continue;
            }

            // Date range filtering (e.g. created_at=2024-01-01 to 2024-12-31)
            if ($this->isDateType($type) && str_contains($value, ' to ')) {
                [$start, $end] = explode(' to ', $value);
                $query->whereBetween($field, [trim($start), trim($end)]);
                continue;
            }

            // Handle multiple comma-separated values (e.g., type=free,!draft)
            $values = explode(',', $value);
            $isEnum = $type === 'enum';
            $query->where(function ($q) use ($field, $values, $isEnum) {
                foreach ($values as $val) {
                    $val = trim($val);
                    if (str_starts_with($val, '!')) {
                        $cleaned = ltrim($val, '!');
                        if ($isEnum) {
                            $q->where($field, '!=', $cleaned);
                        } else {
                            $q->where($field, 'not like', '%' . $cleaned . '%');
                        }
                    } else {
                        if ($isEnum) {
                            $q->orWhere($field, '=', $val);
                        } else {
                            $q->orWhere($field, 'like', '%' . $val . '%');
                        }
                    }
                }
            });


        }

        return $query;
    }

    /**
     * Get the column types for a table.
     *
     * @param string $table
     * @param array $columns
     * @return array
     */
    protected function getColumnTypes(string $table, array $columns): array
    {
        $types = [];
        foreach ($columns as $column) {
            $types[$column] = Schema::getColumnType($table, $column);
        }
        return $types;
    }

    /**
     * Check if a type is numeric.
     *
     * @param string $type
     * @return bool
     */
    protected function isNumericType(string $type): bool
    {
        return in_array($type, $this->numericTypes);
    }

    /**
     * Check if a type is date-like.
     *
     * @param string $type
     * @return bool
     */
    protected function isDateType(string $type): bool
    {
        return in_array($type, $this->dateTypes);
    }
}
