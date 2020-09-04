<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu" x-placement="top-end" style="position: absolute; transform: translate3d(-76px, -144px, 0px); top: 0px; left: 0px; will-change: transform;">
        <li>
            <button type="button" class="btn btn-link view" data-toggle="modal" data-target="#product-{{$uuid}}"><i class="fa fa-eye"></i> View </button>
        </li>
        <li>
            <a href="{{ route('product.edit', $uuid)}} " class="btn btn-link"><i class="fa fa-edit"></i> Edit</a>
        </li>
        <form action="{{ route('product.destroy', $uuid) }}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <li>
                <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                {{csrf_field()}}
            </li>
        </form>
    </ul>
</div>

<div  class="modal fade" id="product-{{$uuid}}" style="padding-right: 17px; opacity: 1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{$name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div id="carouselExampleIndicators-{{$id}}" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    @foreach ($image as $index => $p_img)
                                        <li data-target="#carouselExampleIndicators-{{$id}}" data-slide-to="{{$index}}" class="{{$index == 0 ? 'active' : ''}}"></li>
                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    @foreach ($image as $index => $p_img)
                                        <!-- @php
                                            $image_location = Storage::url('products/large/'.$p_img)
                                        @endphp -->
                                        <div class="carousel-item {{$index == 0 ? 'active' : ''}}">
                                            <img class="d-block w-100" src="{{$model->product_image('products/large/'.$p_img)}}" alt="Product Image">
                                        </div>
                                    @endforeach

                                    {{--
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="https://placehold.it/900x500/f39c12/ffffff&amp;text=I+Love+Bootstrap" alt="Third slide">
                                    </div> --}}
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators-{{$id}}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators-{{$id}}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                    </a>
                                </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <h3 class="my-3">{{$name}}</h3>
                            {!! $description !!}

                            <hr>
                            <h5><strong>Available Between</strong></h5>


                                <div class="callout callout-info">
                                    <p class="h5">{{$model->carbonParseDate($start_date)->format('d F Y')}}</p>
                                            &
                                    <p class="h5">{{$model->carbonParseDate($end_date)->format('d F Y')}}</p>
                                </div>


                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                      <div class="description-block">
                                          <span class="description-text">Stock Unit</span>
                                        <h5 class="description-header">{{$model->unit->unit_code}}</h5>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 border-right">
                                      <div class="description-block">
                                          <span class="description-text">Purcahse Unit</span>
                                        <h5 class="description-header">{{$model->purchaseUnit->unit_code}}</h5>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                      <div class="description-block">
                                        <span class="description-text">Sale Unit</span>
                                        <h5 class="description-header">{{$model->saleUnit->unit_code}}</h5>
                                      </div>
                                      <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                </div>

                            <div class="bg-gray py-2 px-3 mt-4">
                                <h2 class="mb-0">
                                    ₦ {{$price}}
                                </h2>
                                <h4 class="mt-0">
                                    <small>per {{$model->unit->unit_name}}</small>
                                </h4>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <div class="modal-footer justify-content-between">
                <div></div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
