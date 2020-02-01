@extends('admin.master')

@section('title')
    Reviews 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Reviews</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">Reviews</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')
@if(count($reviews) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Reviews</h3>

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
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th class = "text-center">Created At</th>
                    <th class = "text-center">Client</th>
                    <th class = "text-center">Rate</th>      
                    <th class = "text-center">Body</th>
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$review->created_at}}</td>
                            <td class = "text-center">{{$review->client->name}}</td> 
                            <td class = "text-center">{{$review->rate}}</td> 
                            <td class = "text-center">{{$review->body}}</td> 
                            
                            <td class = "text-center">
                                <button  {{ Laratrust::can('delete_review') ?  '' : 'disabled'}}  onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['review.destroy' , $review->id ] , 
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
          {{$reviews->appends(['restaurant_id' => $res_id])->links()}}
          <!-- /.card -->
        </div>
    </div>
    
@else 
    <p>No reviews available</p> 
@endif


@endsection