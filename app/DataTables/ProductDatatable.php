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
                return '<img src="'.$query->productImage('/products/small/', $image[0]).'">';
            })
            ->editColumn('sale unit', function($query) {
                return $query->saleUnit->unit_code;
            })
            ->editColumn('purchase unit', function($query) {
                return $query->purchaseUnit->unit_code;
            })
            ->editColumn('status', function($query) {
                if($query->status == 0) {
                    return '<span class="right badge badge-warning"><i class="fa fa-clock"></i> In Stock</span>';
                } elseif($query->status ==1) {
                    return '<span class="right badge badge-success"><i class="fa fa-clock"></i> In Order</span>';
                } else {
                    return '<span class="right badge badge-danger"><i class="fa fa-clock"></i> Sold Out</span>';
                }
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
            ->addColumn('Action', 'pages.datatable.product.action')
            ->rawColumns(['Action', 'description', 'image', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $product)
    {
        return $product->newQuery()->with('unit')->with('saleUnit')->with('purchaseUnit');
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
            Column::make('sale unit', 'saleUnit.unit_code'),
            Column::make('purchase unit', 'purchaseUnit.unit_code'),
            Column::make('price'),
            Column::make('status')
                    ->searchable(false),
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
