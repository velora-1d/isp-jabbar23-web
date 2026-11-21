<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasFilters
{
    /**
     * Apply global filters (year, month, date range, search) to query
     */
    protected function applyGlobalFilters(Builder $query, Request $request, array $options = []): Builder
    {
        $dateColumn = $options['dateColumn'] ?? 'created_at';
        $searchColumns = $options['searchColumns'] ?? [];

        // Year filter
        if ($request->filled('year')) {
            $query->whereYear($dateColumn, $request->year);
        }

        // Month filter
        if ($request->filled('month')) {
            $query->whereMonth($dateColumn, $request->month);
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate($dateColumn, '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate($dateColumn, '<=', $request->end_date);
        }

        // Search filter
        if ($request->filled('search') && !empty($searchColumns)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchColumns, $searchTerm) {
                foreach ($searchColumns as $column) {
                    // Handle relationship columns (e.g., 'customer.name')
                    if (str_contains($column, '.')) {
                        [$relation, $field] = explode('.', $column, 2);
                        $q->orWhereHas($relation, function ($subQuery) use ($field, $searchTerm) {
                            $subQuery->where($field, 'LIKE', "%{$searchTerm}%");
                        });
                    } else {
                        $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                    }
                }
            });
        }

        return $query;
    }

    /**
     * Apply status filter
     */
    protected function applyStatusFilter(Builder $query, Request $request, string $column = 'status'): Builder
    {
        if ($request->filled('status')) {
            $query->where($column, $request->status);
        }

        return $query;
    }

    /**
     * Apply relationship filter (e.g., customer_id, technician_id)
     */
    protected function applyRelationFilter(Builder $query, Request $request, string $filterName, string $column = null): Builder
    {
        $column = $column ?? $filterName;

        if ($request->filled($filterName)) {
            $query->where($column, $request->$filterName);
        }

        return $query;
    }

    /**
     * Get common filter options for views
     */
    protected function getFilterOptions(): array
    {
        $currentYear = date('Y');

        return [
            'years' => range($currentYear, $currentYear - 5),
            'months' => [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ],
        ];
    }
}
