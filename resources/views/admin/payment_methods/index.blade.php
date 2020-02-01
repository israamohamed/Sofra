@extends('admin.master')

@section('title')
    payment methods 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>payment methods</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">payment methods</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
<a href = "{{url(route('paymentMethod.create'))}}" class = "btn btn-info mx-3 float-right"><i class = "fa fa-plus"></i>  New Payment method</a><br><br><br>
@include('admin.includes.flash-message')
@if(count($payment_methods) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Payment methods</h3>

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
                    @foreach($payment_methods as $payment_method)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$payment_method->name}}</td>
                            <td class = "text-center">
                              <a   href = "{{url(route('paymentMethod.edit' , $payment_method->id))}}" class = "btn btn-success btn-sm"><i class = "fa fa-edit"></i></a>
                            </td>
                            <td class = "text-center">
                                <button  {{ Laratrust::can('delete_paymentMethod') ?  '' : 'disabled'}}   onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['paymentMethod.destroy' , $payment_method->id ] , 
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
    <p>No payment methods available</p> 
@endif


@endsection