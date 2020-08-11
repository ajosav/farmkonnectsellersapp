@extends('layouts.farmkonnect')
@section('content')
  <div class="container">
    <h3 class="text-success mt-3">Products</h3>
    <div class="row mt-3 ml-2">
      @foreach ($products as $product)
        <div class="col-md-4">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">{{$product->name}}</h3>
              <div class="card-tools">
                <span class="badge badge-primary">Qty: {{$product->quantity}}</span>
              </div>
            </div>
            <div class="card-body">
             
              <img src="{{$product->productImage('/products/large/', $product->image[0])}}" class="img-fluid">
              <div>
                <span class="badge badge-warning">Category: </span> {{$product->category}}
              </div>
              <div>
                <span class="badge badge-secondary">Price: </span> &#8358;{{number_format($product->price)}}
              </div>
            </div>
            <div class="card-footer">
              <button class="btn btn-success"data-toggle="modal" data-target="#orderproduct{{$product->id}}-modal-lg">Order Product</button>
            </div>
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="orderproduct{{$product->id}}-modal-lg" style="opacity: 1" tabindex="-1" role="dialog" aria-labelledby="orderproduct{{$product->id}}Title" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{$product->code}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-5">
                    <div id="carouselExampleIndicators{{$product->id}}" class="carousel slide" data-ride="carousel">
                      <ol class="carousel-indicators">
                        @foreach($product->image as $index => $image)
                          <li data-target="#carouselExampleIndicators" data-slide-to="$index" class="{{$index == 0 ? 'active' : ''}}"></li>
                        @endforeach 
                      </ol>
                      <div class="carousel-inner">
                        @foreach($product->image as $index => $image)
                          <div class="carousel-item {{$index == 0 ? 'active' : ''}}">
                            <img class="d-block w-100" src="{{$product->productImage('/products/large/', $image)}}" alt="First slide">
                          </div>
                        @endforeach 
                      </div>
                      <a class="carousel-control-prev" href="#carouselExampleIndicators{{$product->id}}" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#carouselExampleIndicators{{$product->id}}" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-7">
                    <p><span class="font-weight-bold">Description:</span> {!! $product->description !!}</p>
                    <p><span class="font-weight-bold">Quantity Available:</span> {{$product->quantity}}</p>
                    <p><span class="font-weight-bold">Unit in Stock:</span> {{$product->unit->unit_name}} </p> 
                    <p><span class="font-weight-bold">Price per Unit :</span>&#8358;{{number_format($product->price)}} </p> 
                    <p><span class="font-weight-bold">Product Availablity:</span> {{$product->start_date}} to {{$product->end_date}}</p>
                    <form method="post">
                      <p>
                        <label for="exampleFormControlInput1">Choose Unit you want to purchase in</label>
                        <select class="form-control unit" name="unit" id="{{$product->uuid}}">
                          <option value="">Select a Unit</option>
                          @php
                            if($product['purchase_unit_id'] !== $product['sale_unit_id']){
                          @endphp
                          <option value="{{$product->purchase_unit_id}}">{{$product->purchaseUnit->unit_name}} </option>
                          <option value="{{$product->sale_unit_id}}">{{$product->saleUnit->unit_name}}</option>
                          @php
                            }else{
                          @endphp
                          <option value="{{$product->purchase_unit_id}}">{{ $product->purchaseUnit->unit_name }}</option>
                          @php
                            }
                          @endphp
                        </select>
                      </p>
                      <p>
                        <label for="exampleFormControlInput1">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity{{$product->uuid}}">
                      </p> 
                      <input type="hidden" name="totalprice" id="totalprice{{$product->uuid}}">
                    </form>
                    <p><span class="font-weight-bold">Price: &#8358;</span><span id="price{{$product->uuid}}" class="price{{$product->uuid}}"></span> </p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success order_product" id="{{$product->uuid}}">Order</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        
    @endforeach
    </div>
    {{ $products->links() }}
  </div>

@stop

@push('scripts')
  <script>


    $(document).ready(function(){
      $("select.unit").change(function(){
        let id =$(this).attr('id');
          $("#quantity"+id).val("");
          if($("select#"+id).children("option:selected").val() == ""){
            swal.fire("Please select a unit");
          }else{

            $("#quantity"+id).keyup(
              _.debounce(function() {
                 var unit_id = $("select#"+id).children("option:selected").val();
                  var quantity = $("#quantity"+id).val();
                axios.post('./cal_price', {uuid: id, unit_id:unit_id, quantity:quantity})
                .then((response) => {
                  //console.log(response.data)
                  $("span#price"+id).empty();
                  $("span#price"+id).html(response.data.price_of_product);
                  $("#totalprice"+id).val(response.data.price_of_product);
                  if(response.data.msg !== ''){
                    swal.fire("The quantity you want to buy is more than the available quantity");
                  }
                })
                .catch(error => {
                  swal.fire('error', error.response.message, 'error')
                  //console.log(error.response.message);
                })
              }, 1500)
              
            );
          }
      });

      $(".order_product").click(function(){
        let id =$(this).attr('id');
        if($("select#"+id).children("option:selected").val() == "" || $("#quantity"+id).val() == ""){
            swal.fire("Please fill all fields");
        }else{
          var unit_id = $("select#"+id).children("option:selected").val();
          var quantity = $("#quantity"+id).val();
          var price = $("span#price"+id).html();
          var totalprice = $("#totalprice"+id).val();
          //alert(totalprice);
          axios.post('./process_order', {product_id: id, unit_id:unit_id, quantity_ordered:quantity, totalprice:totalprice})
            .then((response) => {
              //console.log(response.data);
              if(response.data.msg !== ''){
                swal.fire("Success", "Your order has been placed successfully");
              }
            })
            .catch(error => {
              //swal('error', error.response.message, 'error')
              console.log(error.response.message);
            })
        }
      });
    });
  </script>

@endpush