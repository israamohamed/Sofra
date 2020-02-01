@extends('admin.master')

@section('title')
    {{$client->name}} 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Client : {{$client->name}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('client.index'))}}">Clients</a></li>
            <li class="breadcrumb-item active">{{$client->name}}</li>
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
          <a href = "{{url(route('order.index' , ['client_id' => $client->id]))}}" target = "_Blank">
            <span class="info-box-text">Number of orders</span>
            <span class="info-box-number">
              {{$client->orders_count}}
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
          <a href = "{{url(route('review.index' , ['client_id' => $client->id]))}}" target = "_Blank">
            <span class="info-box-text">Reviews</span>
            <span class="info-box-number">{{$client->reviews_count}}</span>
          </a>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

  

    </div>

@endsection