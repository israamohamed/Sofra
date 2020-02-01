@extends('admin.master')

@section('title')
    Offers 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Offers</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('restaurant.index'))}}">Restaurants</a></li>
            <li class="breadcrumb-item active">Offers</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')
@if(count($offers) > 0)


<div class="card card-solid">
    <div class="card-body pb-0">
      <div class="row d-flex justify-content-center align-items-stretch">
        @foreach($offers as $offer)
        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
          <div class="card bg-light">
            <div class="card-header text-muted border-bottom-0 text-center">
              {{$offer->title}}
            </div>
            <div class="card-body pt-0">
              <div class="row">
                <div class="col-10">
                  <div style = "width:100%;" > <img style = "height:200px;" class = "w-100" src = "{{asset('offers_images/' . $offer->image)}}"> </div><br>
                  <p class="text-muted text-sm"><b>Description: </b> {{$offer->description}} </p>
                  <ul class="ml-4 mb-0 fa-ul text-muted">
                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-clock"></i></span> From: {{$offer->from}}</li>
                    <br>
                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-clock"></i></span> To : {{$offer->to}}</li>
                  </ul>
                </div>
                <div class="col-5 text-center">
                  <img src="../../dist/img/user1-128x128.jpg" alt="" class="img-circle img-fluid">
                </div>
                <div class = "my-1">
                    <button  {{ Laratrust::can('delete_offer') ?  '' : 'disabled'}}  onclick = "deleteData(this)" class = "btn btn-danger btn-sm"><i class = "fa fa-trash mx-1"></i> Delete</button>
                    {!!Form::open([
                        'route' => ['offer.destroy' , $offer->id ] , 
                        'method' => 'delete'
                    ]) !!}
                    
                    
                    {!!Form::close()!!}
                </div>
              </div>
            </div>
            
          </div>
        </div>
        @endforeach
      </div>
    </div>
    <!-- /.card-body -->
    {{$offers->appends(['restaurant_id' => $res_id])->links()}}
  </div>
  
@else 
    <p>No offers available</p> 
@endif


@endsection