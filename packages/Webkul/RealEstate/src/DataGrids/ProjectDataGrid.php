<?php

namespace Webkul\RealEstate\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProjectDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('real_estate_projects')
            ->select(
                'real_estate_projects.id',
                'real_estate_projects.project_name',
                'real_estate_projects.project_code',
                'real_estate_projects.developer_name',
                'real_estate_projects.project_type',
                'real_estate_projects.city',
                'real_estate_projects.rera_number',
                'real_estate_projects.status',
                'real_estate_projects.created_at',
            );

        $this->addFilter('id', 'real_estate_projects.id');
        $this->addFilter('project_name', 'real_estate_projects.project_name');
        $this->addFilter('project_code', 'real_estate_projects.project_code');
        $this->addFilter('developer_name', 'real_estate_projects.developer_name');
        $this->addFilter('project_type', 'real_estate_projects.project_type');
        $this->addFilter('city', 'real_estate_projects.city');
        $this->addFilter('rera_number', 'real_estate_projects.rera_number');
        $this->addFilter('status', 'real_estate_projects.status');
        $this->addFilter('created_at', 'real_estate_projects.created_at');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'project_name',
            'label' => trans('real_estate::app.projects.index.datagrid.project_name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'project_code',
            'label' => trans('real_estate::app.projects.index.datagrid.project_code'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'developer_name',
            'label' => trans('real_estate::app.projects.index.datagrid.developer_name'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'project_type',
            'label' => trans('real_estate::app.projects.index.datagrid.project_type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('real_estate::app.projects.type-apartment'), 'value' => 'apartment'],
                ['label' => trans('real_estate::app.projects.type-villa'), 'value' => 'villa'],
                ['label' => trans('real_estate::app.projects.type-plot'), 'value' => 'plot'],
                ['label' => trans('real_estate::app.projects.type-commercial'), 'value' => 'commercial'],
            ],
            'closure' => function ($row) {
                return match ($row->project_type) {
                    'apartment' => trans('real_estate::app.projects.type-apartment'),
                    'villa' => trans('real_estate::app.projects.type-villa'),
                    'plot' => trans('real_estate::app.projects.type-plot'),
                    'commercial' => trans('real_estate::app.projects.type-commercial'),
                    default => ucfirst($row->project_type),
                };
            },
        ]);

        $this->addColumn([
            'index' => 'city',
            'label' => trans('real_estate::app.projects.index.datagrid.city'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'rera_number',
            'label' => trans('real_estate::app.projects.index.datagrid.rera_number'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
            'closure' => fn ($row) => $row->rera_number ?: '--',
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('real_estate::app.projects.index.datagrid.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'filterable_type' => 'dropdown',
            'filterable_options' => [
                ['label' => trans('real_estate::app.projects.status-upcoming'), 'value' => 'upcoming'],
                ['label' => trans('real_estate::app.projects.status-active'), 'value' => 'active'],
                ['label' => trans('real_estate::app.projects.status-on-hold'), 'value' => 'on_hold'],
                ['label' => trans('real_estate::app.projects.status-completed'), 'value' => 'completed'],
            ],
            'closure' => function ($row) {
                return match ($row->status) {
                    'active' => '<span class="badge badge-round badge-primary text-emerald-800 bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-300">'.trans('real_estate::app.projects.status-active').'</span>',
                    'completed' => '<span class="badge badge-round badge-primary text-blue-800 bg-blue-100 dark:bg-blue-950 dark:text-blue-300">'.trans('real_estate::app.projects.status-completed').'</span>',
                    'on_hold' => '<span class="badge badge-round badge-primary text-amber-800 bg-amber-100 dark:bg-amber-950 dark:text-amber-300">'.trans('real_estate::app.projects.status-on-hold').'</span>',
                    'upcoming' => '<span class="badge badge-round badge-primary text-purple-800 bg-purple-100 dark:bg-purple-950 dark:text-purple-300">'.trans('real_estate::app.projects.status-upcoming').'</span>',
                    default => '<span class="badge badge-round badge-primary">'.ucfirst(str_replace('_', ' ', $row->status)).'</span>',
                };
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('real_estate::app.projects.index.datagrid.created_at'),
            'type' => 'date',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true,
            'filterable_type' => 'date_range',
            'closure' => fn ($row) => core()->formatDate($row->created_at),
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('real_estate.projects.view') || bouncer()->hasPermission('real_estate.projects')) {
            $this->addAction([
                'index' => 'view',
                'icon' => 'icon-eye',
                'title' => trans('real_estate::app.projects.index.datagrid.view'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.real_estate.projects.view', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('real_estate.projects.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('real_estate::app.projects.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.real_estate.projects.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('real_estate.projects.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('real_estate::app.projects.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn ($row) => route('admin.real_estate.projects.delete', $row->id),
            ]);
        }
    }
}
