@extends('admin.master')
@inject('model' , 'App\Models\Region')

@section('title')
    Edit region 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit region</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url(route('region.index'))}}">regions</a></li>
            <li class="breadcrumb-item active">edit region</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
    @include('admin.includes.flash-message')

    {!!Form::model($model , [
        'route'  => ['region.update' , $region->id] ,
        'class'  => 'w-50' ,
        'method' => 'put'
    ])!!}


    <div class = "form-group">
            {!!Form::label('name', 'Name') !!}
            {!!Form::text('name', $region->name , ['class' => 'form-control' , 'placeholder' => 'City Name'])!!}
    </div>

    <div class = "form-group">
            {!!Form::label('city_id', 'city') !!}
            @inject('cities' , 'App\Models\City')
            {!!Form::select('city_id', $cities->all()->pluck('name' , 'id')->toArray() , $region->city->id , ['class' => 'form-control' , 'placeholder' => 'City Name'])!!}
    </div>

    <div class = "form-group">
        {!!Form::submit('Edit' , ['class' => 'btn btn-primary'])!!}
    </div>

    {!!Form::close()!!}

@endsection