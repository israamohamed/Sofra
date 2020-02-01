@extends('admin.master')

@section('title')
    {{$restaurant->name}} 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Restaurant : {{$restaurant->name}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('restaurant.index'))}}">Restaurants</a></li>
            <li class="breadcrumb-item active">{{$restaurant->name}}</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

        <div class="info-box-content">
          <a href = "{{url(route('order.index' , ['restaurant_id' => $restaurant->id]))}}" target = "_Blank">
            <span class="info-box-text">Number of orders</span>
            <span class="info-box-number">
              {{$restaurant->orders_count}}
            </span>
          </a>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

        <div class="info-box-content">
          <a href = "{{url(route('review.index' , ['restaurant_id' => $restaurant->id]))}}" target = "_Blank">
            <span class="info-box-text">Reviews</span>
            <span class="info-box-number">{{$restaurant->reviews_count}}</span>
          </a>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

        <div class="info-box-content">
          <a href = "{{url(route('product.index' , ['restaurant_id' => $restaurant->id]))}}" target = "_Blank">
            <span class="info-box-text">Products</span>
            <span class="info-box-number">{{$restaurant->products_count}}</span>
          </a>
          </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

        <div class="info-box-content">
          <a href = "{{url(route('offer.index' , ['restaurant_id' => $restaurant->id]))}}" target = "_Blank">
            <span class="info-box-text">Offers</span>
            <span class="info-box-number">{{$restaurant->offers_count}}</span>
          </a>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
  
          <div class="info-box-content">
            <button type = "button" class = "btn btn-default" data-toggle="modal" data-target="#show-categories">
              <span class="info-box-text">Categories</span>
              <span class="info-box-number">{{count($restaurant->categories)}}</span>
            </button>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
  </div>

  <div class="modal fade" id="show-categories" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Categories</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            @if(count($restaurant->categories))
              @foreach($restaurant->categories as $category)
                <p>{{$category->name}}</p>
              @endforeach
            @else 
              <p>No categories to show
            @endif
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    @push('script')
      <script>
        $('.show_in_reqeust_details_btn').click(function()
        {
          var id = $(this).attr("id");
          $.ajax({
            method : "get" ,
            url    : "{{url(route('restaurant.categories'))}}" , 
            data   : {id  : id} ,
            //
            success : function(data) 
            {
              if(data.status == 1)
              {
                var request = data.data;
                $("#show-categories .modal-body").empty();
                console.log(request);
                
                $("#show-restaurant .modal-body").append(
                  
                );
              }
              
            }
          });
        });
      </script>
  @endpush
@endsection