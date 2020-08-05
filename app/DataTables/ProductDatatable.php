<?php

namespace App\DataTables;

use App\Model\Product;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('image', function($query) {
                $image = explode(',', $query->image);
                $image_link = Storage::url('/products/small/'.$image[0]);
                return '<img src="'.$image_link.'">';
            })
            ->editColumn('sale unit', function($query) {
                return $query->saleUnit->unit_code;
            })
            ->editColumn('purchase unit', function($query) {
                return $query->purchaseUnit->unit_code;
            })
            ->editColumn('price', function($query) {
                return '₦' . $query->price;
            })
            ->editColumn('created_at', function($query) {
                return $query->created_at->diffForHumans();
            })
            // ->addColumn('description', 'productdatatable.action')
            ->editColumn('updated_at', function($query) {
                return $query->updated_at->diffForHumans();
            })
            // ->addColumn('Action', '
            //     <form action="{{ route(\'product.destroy\', $uuid) }}" method="POST">
            // <input type="hidden" name="_method" value="DELETE">
            // <input type="submit" name="submit" value="' . __('Delete') . '" class="btn btn-danger" onClick="return confirm(\'Are you sure?\')"">
            // {{csrf_field()}}
            // </form>')
            ->addColumn('Action', '
            <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu" x-placement="top-end" style="position: absolute; transform: translate3d(-76px, -144px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <li>
                        <button="type" class="btn btn-link view"><i class="fa fa-eye"></i> View
                    </button="type"></li><li>
                    <a href="http://salepropos.test/products/5/edit" class="btn btn-link"><i class="fa fa-edit"></i> Edit</a>
                </li>
                <form action="{{ route(\'product.destroy\', $uuid) }}" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <li>
                        <button type="submit" class="btn btn-link text-danger" onclick="return confirm(\'Are you sure?\')"><i class="fa fa-trash"></i> Delete</button>
                        {{csrf_field()}}
                    </li>
                </form>
                </ul>
            </div>')
            ->rawColumns(['Action', 'description', 'image']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->newQuery();
        // return $model->newQuery()->where('id', 1);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('productdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1);
                    // ->buttons(
                    //     Button::make('create'),
                    //     Button::make('export'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('image'),
            Column::make('name'),
            Column::make('description'),
            Column::make('sale unit'),
            Column::make('purchase unit'),
            Column::make('price'),
            Column::make('status'),
            Column::make('category'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('Action')
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
                    ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
