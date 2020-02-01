@extends('admin.master')

@section('title')
    Clients 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Clients</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">Clients</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')
<div class="card-header">
  <h3 class="card-title">Clients</h3>

  <div class="card-tools">
    {!!Form::open([

      'method'=> 'GET' , 
      'class' => 'input-group input-group-sm' , 
      'style' => 'width: 150px;'
    ])!!}


      {!!Form::text('table_search' , null , ['class' => 'form-control float-right' , 'placeholder' => 'Search' ])!!}
      <div class="input-group-append">
        {!!Form::button('<i class="fa fa-search"></i>', ['type' => 'submit', 'class' => 'btn btn-default'] )  !!}
        
    {!!Form::close()!!}
  </div>
</div>
</div>
@if(count($clients) > 0)
    <div class="row">
        <div class="col-12">
          <div class="card">
            
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th class = "text-center">Name</th>
                    <th class = "text-center">Email</th>
                    <th class = "text-center">Phone</th>      
                    <th class = "text-center">Active Now ?</th>
                    <th class = "text-center">Region</th> 
                    <th class = "text-center">Show details</th>
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$client->name}}</td>
                            <td class = "text-center">{{$client->email}}</td> 
                            <td class = "text-center">{{$client->phone}}</td> 
                            <td class = "text-center"><button id = {{$client->id}}  {{ Laratrust::can('toggle_activation_client') ?  '' : 'disabled'}}   type = "button" class = "toggle-activate-button btn btn-block {{$client->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary'}}">{{$client->is_active ? 'Active' : 'Not Active'}}</button></td> 
                            <td class = "text-center">{{$client->region->name}}</td>
                            <td class = "text-center"><a href = "{{url(route('client.show' , $client->id))}}" class = "btn btn-primary">Show Details</a></td>
                            <td class = "text-center">
                                <button   {{ Laratrust::can('delete_client') ?  '' : 'disabled'}}   onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['client.destroy' , $client->id ] , 
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
    <p>No clients available</p> 
@endif
@push('script')
    <script>
   
      $(".toggle-activate-button").click(function()
      {
        var button = $(this);
        var id = $(this).attr('id');
        var title = ($(this).text()=="Active" ? 'Deactivation' : 'Activation');
        var confirmButtonText = ($(this).text()=="Active" ? 'Deactive' : 'Active');
        var text = 'Are you sure you want to ' +  ($(this).text()=="Active" ? 'deactivte' : 'activate') + ' this client ? ' ;

        Swal.fire({
          title: title,
          text: text,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: confirmButtonText,
          cancelButtonText: 'Cancel'

        }).then((result) => {
          if (result.value) 
          {
            $.ajax({
              method : "post" , 
              url : "{{url(route('client.toggle_activation'))}}" ,
              data : {_token: "{{csrf_token()}}"  , id : id} , 
              success: function(data)
              {
                if(data.status ==1)
                {
                  if(data.data == 1) //active
                  {
                    button.text("Active");
                    button.removeClass('bg-gradient-secondary').addClass('bg-gradient-success');
                  }
                  else 
                  {
                    button.text("Not Active");
                    button.removeClass('bg-gradient-success').addClass('bg-gradient-secondary');
                  }
                }
              }
            });
            
          } else if (result.dismiss === Swal.DismissReason.cancel) 
          {
            
          }
      })
      });
      </script>
@endpush

@endsection