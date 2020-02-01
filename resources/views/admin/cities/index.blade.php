@extends('admin.master')

@section('title')
    cities 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>cities</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">cities</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
<a href = "{{url(route('city.create'))}}" class = "btn btn-info mx-3 float-right"><i class = "fa fa-plus"></i>  New City</a><br><br>
@include('admin.includes.flash-message')
@if(count($cities) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Cities</h3>

              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class = "text-center">Edit</th>  
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($cities as $city)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$city->name}}</td>
                            <td class = "text-center">
                              <a href = "{{url(route('city.edit' , $city->id))}}" class = "btn btn-success btn-sm"><i class = "fa fa-edit"></i></a>
                            </td>
                            <td class = "text-center">
                                <button  {{ Laratrust::can('delete_city') ?  '' : 'disabled'}}  onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['city.destroy' , $city->id ] , 
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
        </div>
    </div>
@else 
    <p>No cities available</p> 
@endif


@endsection