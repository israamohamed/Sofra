@extends('admin.master')

@section('title')
    Roles 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Roles</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">Roles</li>
          </ol>
        </div>
      </div>
@stop

@section('content')
<a href = "{{url(route('role.create'))}}" class = "btn btn-info mx-3 float-right"><i class = "fa fa-plus"></i>  New Role</a><br><br>
@include('admin.includes.flash-message')
@if(count($roles) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Roles</h3>

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
                    <th class = "text-center">Name</th>
                    <th class = "text-center">Display name</th>
                    <th class = "text-center">Description</th>
                    <th class = "text-center">No of permissions</th>
                    <th class = "text-center">No of users</th>
                    <th class = "text-center">Edit</th>  
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$role->name}}</td>
                            <td class = "text-center">{{$role->display_name}}</td>
                            <td class = "text-center">{{$role->description ? $role->description : '-'}}</td>
                            <td class = "text-center">{{$role->permissions->count()}}</td>
                            <td class = "text-center">{{$role->users->count()}}</td>
                            <td class = "text-center">
                              <a href = "{{url(route('role.edit' , $role->id))}}" class = "btn btn-success btn-sm"><i class = "fa fa-edit"></i></a>
                            </td>
                            <td class = "text-center">
                                <button  {{ Laratrust::can('delete_role') ?  '' : 'disabled'}}  onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['role.destroy' , $role->id ] , 
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
    <p>No roles available</p> 
@endif


@endsection