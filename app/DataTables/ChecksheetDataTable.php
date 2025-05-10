<?php

namespace App\DataTables;

use App\Models\Checksheet;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class ChecksheetDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('updated_at', function ($checksheet) {
                return Carbon::parse($checksheet->updated_at)->format('d/m/Y');
            })
            ->editColumn('supplier.name', function ($checksheet) {
                return $checksheet->supplier ? $checksheet->supplier->name : 'N/A';
            })
            ->editColumn('qe.name', function ($checksheet) {
                return $checksheet->qe ? $checksheet->qe->name : 'N/A';
            })
            ->editColumn('product.name', function ($checksheet) {
                return $checksheet->product ? $checksheet->product->name : 'N/A';
            })
            ->setRowId('id');
    }

    public function query(Checksheet $model): QueryBuilder
    {
        return $model->newQuery()->with(['supplier', 'qe', 'product']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('checksheet-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5)
            ->selectStyleSingle()
            ->buttons([
                Button::make('add'),
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('supplier.name')->title('Supplier'),
            Column::make('qe.name')->title('QE'),           
            Column::make('product.name')->title('Product'), 
            Column::make('status')->title('Status'),
            Column::make('updated_at')->title('Updated At'),
        ];
    }
    protected function filename(): string
    {
        return 'Checksheet_' . date('YmdHis');
    }
}
