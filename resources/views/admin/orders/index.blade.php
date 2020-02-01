@extends('admin.master')

@section('title')
    Orders 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Orders</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">Orders</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')
@if(count($orders) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Orders</h3>

              <div class="card-tools">
                {!!Form::open([
            
                  'method'=> 'GET' , 
                  'class' => 'input-group input-group-sm' , 
                  'style' => 'width: 250px;'
                ])!!}
                 
                    {!!Form::select('status' , ['pending' => 'pending' , 'accepted' => 'accepted' , 'rejected' => 'rejected' , 'delivered' => 'delivered' , 'declined' => 'declined' , 'confirmed' => 'confirmed'] ,  null , ['class' => 'form-control float-right status' , 'placeholder' => 'Search be order status' ])!!}
                    @if(isset($res_id))
                        {!!Form::hidden('restaurant_id' , $res_id )!!}
                    @else(isset($client_id))
                        {!!Form::hidden('client_id' , $client_id )!!}
                    @endif
                  <div class="input-group-append">
                    {!!Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-default'] )  !!}
                    
                {!!Form::close()!!}
              </div>
            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th class = "text-center">Created At</th>
                    <th class = "text-center">Status</th>
                    <th class = "text-center">Payment Method</th>      
                    <th class = "text-center">Total cost</th>
                    <th class = "text-center">Commission</th>
                    <th class = "text-center">Net</th> 
                    <th class = "text-center">Show products</th>
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$order->created_at}}</td>
                            <td class = "text-center">{{$order->state}}</td> 
                            <td class = "text-center">{{$order->paymentMethod->name}}</td> 
                            <td class = "text-center">{{$order->total_cost}}</td> 
                            <td class = "text-center">{{$order->commission}}</td>
                            <td class = "text-center">{{$order->net}}</td>
                            <td class = "text-center"><button id = {{$order->id}} type = "button" class = "show_products_btn btn btn-warning" data-toggle="modal" data-target="#show-products"> Show Products </button></td>
                            <td class = "text-center">
                                <button  {{ Laratrust::can('delete_order') ?  '' : 'disabled'}}  onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['order.destroy' , $order->id ] , 
                                    'method' => 'delete'
                                ]) !!}
                                
                                
                                {!!Form::close()!!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          {{ isset($res_id) ? $orders->appends(['restaurant_id' => $res_id])->links() :  $orders->appends(['client_id' => $client_id])->links() }}
        </div>
    </div>
    <div class="modal fade" id="show-products" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Order Products</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
@else 
    <p>No orders available</p> 
@endif
@push('script')
    <script>
      $('.show_products_btn').click(function()
      {
        $("#show-products .modal-body").empty();
        var id = $(this).attr("id");
        var url = "{{url(route('order_products.show' , ':id'))}}";
        url = url.replace(':id' , id);
        console.log(url);
        $.ajax({
          method : "get" ,
          url    : url , 
          
          success : function(data) 
          {
            if(data.status == 1)
            {
              var products = data.data;
              
              console.log(products);
              $("#show-products .modal-body").append(
                "<div class='card-body table-responsive p-0'>" + 
                  "<table class='table table-hover'>" +
                    "<thead>" +
                      "<tr>" +
                        "<th>Name</th>" +
                        "<th>Price</th>" + 
                        "<th>Quantity</th>" + 
                      "</tr>" +
                    "</thead>" +
                    "<tbody>"
              );
              $.each(products, function( index, product ) {
                $("#show-products .modal-body tbody").append(
                  "<tr>" + 
                    "<td>" + product.name + "</td>" + 
                    "<td>" + product.pivot.price + "</td>" + 
                    "<td>" + product.pivot.quantity + "</td>" +
                  "</tr>"
                );
              });

              $("#show-products .modal-body tbody").append(
                    "</tbody>" + 
                  "</table>" + 
                "</div>"
              );
              
            }
            
          }
        });
      });

      $('.status').select2({
            placeholder: 'Search by order status'
      });
    </script>
@endpush

@endsection