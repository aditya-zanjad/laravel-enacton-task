<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\ShortURLVisit;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * This class contains method for creating dataTable for the Short URL link visits.
 *
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 * @access  public
 */
class ShortURLsVisitsDataTable extends DataTable
{
    /**
     * The ID of the short URL
     *
     * @var int $id
     */
    private int $id;

    /**
     * Initialize ID of the short URL
     *
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Build DataTable class.
     *
     * @param   QueryBuilder $query Results from query() method.
     *
     * @return  \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setTransformer(function ($visit) {
                return $this->getTransformedModelData($visit);
            })->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param   \AshAllenDesign\ShortURL\Models\ShortURLVisit $model
     *
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function query(ShortURLVisit $model): QueryBuilder
    {
        $columns = ['operating_system', 'browser', 'browser_version', 'referer_url', 'device_type', 'visited_at'];

        return $model->where('short_url_id', $this->id)
            ->select($columns);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return  \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('short_url_visits_datatable-table')
            ->columns($this->getColumns())
            ->fixedHeader()
            ->fixedColumns(2)
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('operating_system'),
            Column::make('browser'),
            Column::make('browser_version'),
            Column::make('referer_url'),
            Column::make('device_type'),
            Column::make('visited_at'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get model data and transform it & return as an array
     *
     * @param   \AshAllenDesign\ShortURL\Models\ShortURLVisit $model
     *
     * @return  array<string, mixed>
     */
    private function getTransformedModelData(ShortURLVisit $model): array
    {
        return [
            'operating_system'  =>  $model->operating_system,
            'browser'           =>  $model->browser,
            'browser_version'   =>  $model->browser_version,
            'device_type'       =>  ucwords($model->device_type),

            'referer_url'       =>  "
                <a href=\"" . $model->referer_url . "\" target=\"_blank\"> "
                . $model->referer_url
                . "</a>",

            'visited_at'        =>  Carbon::parse($model->visited_at)->format('Y-m-d H:i:s'),
            'created_at'        =>  Carbon::parse($model->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        =>  Carbon::parse($model->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
