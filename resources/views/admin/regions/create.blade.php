@extends('admin.master')
@inject('model' , 'App\Models\Region')

@section('title')
    New region 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>New region</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('region.index'))}}">regions</a></li>
            <li class="breadcrumb-item active">new region</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

    {!!Form::model($model , [
        'route'  => 'region.store' ,
        'class'  => 'w-100 px-5 py-3'
    ])!!}

    @include('admin.includes.flash-message')
    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', null , ['class' => 'form-control' , 'placeholder' => 'Region Name'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('city_id', 'city') !!}
            @inject('cities' , 'App\Models\City')
            {!!Form::select('city_id', $cities->all()->pluck('name' , 'id')->toArray() , null , ['class' => 'form-control' , 'placeholder' => 'City Name'])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Add' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

@endsection