<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\ShortURL;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * This class contains method for building the datatable for 'ShortUrl' model
 *
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 * @access  public
 */
class ShortUrlsDataTable extends DataTable
{
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
            ->setTransformer(function ($shortUrl) {
                return $this->getTransformedModelData($shortUrl);
            })->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param   \AshAllenDesign\ShortURL\Models\ShortURL $model
     *
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function query(ShortURL $model): QueryBuilder
    {
        $columns = ['id', 'destination_url', 'url_key', 'default_short_url', 'created_at', 'updated_at'];

        return $model->where('user_id', auth()->id())
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
            ->setTableId('short_urls_datatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->fixedHeader()
            ->fixedColumns(2)
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
            Column::make('id'),

            Column::make('destination_url')
                ->searchable()
                ->orderable()
                ->addClass('text-wrap text-justify')
                ->width(240),

            Column::make('default_short_url')
                ->addClass('text-center')
                ->width(120),

            Column::make('url_key'),
            Column::make('number_of_visits'),

            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get model and transform its data & return it.
     *
     * @param   \App\Models\ShortUrl $shortUrl
     *
     * @return  array<string, mixed>
     */
    private function getTransformedModelData(ShortUrl $shortUrl): array
    {
        return [
            'id'                =>  $shortUrl->id,
            'destination_url'   =>  "
                <a href=\"" . $shortUrl->destination_url . "\" target=\"_blank\"> "
                . $shortUrl->destination_url
                . "</a>",

            'url_key'           =>  $shortUrl->url_key,

            'default_short_url' =>  "
                <a href=\"" . route('short-urls.redirect', ['shortURLKey' => $shortUrl->url_key]) . "\" target=\"_blank\"> "
                . $shortUrl->default_short_url .
                "</a>",

            'number_of_visits'  =>  $shortUrl->visits->count()
                . " (<a href=\"" . route('short-urls.visits.index', ['id' => $shortUrl->id]) . "\">View Stats</a>)",

            'created_at'        =>  Carbon::parse($shortUrl->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        =>  Carbon::parse($shortUrl->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
