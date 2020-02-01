@extends('admin.master')

@section('title')
    payments 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>payments</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">payments</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
<a href = "{{url(route('payment.create'))}}" class = "btn btn-info mx-3 float-right"><i class = "fa fa-plus"></i>  New Payment</a><br><br>
@include('admin.includes.flash-message')
@if(count($payments) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Restaurants Payments</h3>

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
                    <th class = "text-center">Created at</th>
                    <th class = "text-center">Restaurant</th>
                    <th class = "text-center">Amount</th>
                    <th class = "text-center">Notes</th>
                    <th class = "text-center">date</th>
                    <th class = "text-center">Edit</th>  
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$payment->created_at}}
                            <td class = "text-center">{{$payment->restaurant->name}}</td>
                            <td class = "text-center">{{$payment->paid}}</td>
                            <td class = "text-center">{{$payment->notes}}</td>
                            <td class = "text-center">{{$payment->date}}</td>
                            <td class = "text-center">
                              <a href = "{{url(route('payment.edit' , $payment->id))}}" class = "btn btn-success btn-sm"><i class = "fa fa-edit"></i></a>
                            </td>
                            <td class = "text-center">
                                <button onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['payment.destroy' , $payment->id ] , 
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
    <p>No payments available</p> 
@endif


@endsection