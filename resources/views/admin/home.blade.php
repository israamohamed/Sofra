
@extends('admin.master')

@section('title')
    Home
@endsection
@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Statistics</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Home</li>
          </ol>
        </div>
      </div>
@stop
@section('content')
    <div class = "row">
      
      
       <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$no_of_orders}}</h3>

                <p>New Orders</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
             
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$no_of_restaurants}}</h3>

                <p>Number Restaurants</p>
              </div>
              <div class="icon">
                <i class="fas fa-utensils"></i>
              </div>
              <a href="{{url(route('restaurant.index'))}}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>


        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$no_of_clients}}</h3>

                <p>Number of Clients</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-plus"></i>
              </div>
              <a href="{{url(route('client.index'))}}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
           
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$no_of_reviews}}</h3>

                <p>Reviews</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>{{$no_of_offers}}</h3>

                <p>Offers</p>
              </div>
              <div class="icon">
                <i class="fas fa-tag"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box" style = "background: #ad42a2;">
              <div class="inner">
                <h3>{{$no_of_products}}</h3>

                <p>Products</p>
              </div>
              <div class="icon">
                <i class="fas fa-hamburger"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small card -->
            <div class="small-box" style = "background: #5bc470;">
              <div class="inner">
                <h3>{{$no_of_contacts}}</h3>

                <p>Contacts</p>
              </div>
              <div class="icon">
                <i class="fas fa-address-book"></i>
              </div>
              <a href="{{url(route('contact.index'))}}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
        </div>

    </div>
      


@stop