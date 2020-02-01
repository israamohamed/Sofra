@extends('admin.master')

@section('title')
    Restaurants 
@stop

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Restaurants</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url(route('home'))}}">Home</a></li>
            <li class="breadcrumb-item active">Restaurants</li>
          </ol>
        </div>
      </div>
@stop

@section('content')

@include('admin.includes.flash-message')
<div class="card-header">
  <h3 class="card-title">Restaurants</h3>

  <div class="card-tools">
    {!!Form::open([

      'method'=> 'GET' , 
      // 'route' => 'restaurant.search' ,
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
@if(count($restaurants) > 0)
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
                    <th class = "text-center">availability</th>
                    <th class = "text-center">Active Now ?</th>
                    <th class = "text-center">Region</th> 
                    <th class = "text-center">Show details</th>
                    <th class = "text-center">Delete</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($restaurants as $restaurant)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td class = "text-center">{{$restaurant->name}}</td>
                            <td class = "text-center">{{$restaurant->email}}</td> 
                            <td class = "text-center">{{$restaurant->phone}}</td> 
                            <td class = "text-center">{{$restaurant->availability ? 'yes' : 'no'}}</td> 
                            <td class = "text-center"><button id = {{$restaurant->id}} type = "button"  {{ Laratrust::can('toggle_activation_restaurant') ?  '' : 'disabled'}}  class = "toggle-activate-button btn btn-block {{$restaurant->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary'}}">{{$restaurant->is_active ? 'Active' : 'Not Active'}}</button></td> 
                            <td class = "text-center">{{$restaurant->region->name}}</td>
                            <td class = "text-center"><button id = {{$restaurant->id}}  type = "button" class = "show_in_reqeust_details_btn btn btn-warning" data-toggle="modal" data-target="#show-restaurant"> Show Details </button></td>
                            <td class = "text-center">

                                <button  {{ Laratrust::can('delete_restaurant') ?  '' : 'disabled'}} onclick = "deleteData(this)" class = "btn btn-danger btn-xs"><i class = "fa fa-trash"></i></button>
                                {!!Form::open([
                                    'route' => ['restaurant.destroy' , $restaurant->id ] , 
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
    <div class="modal fade" id="show-restaurant" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-m">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
@else 
    <p>No restaurants available</p> 
@endif
@push('script')
    <script>
      $('.show_in_reqeust_details_btn').click(function()
      {
        var id = $(this).attr("id");
        $.ajax({
          method : "get" ,
          url    : "{{url(route('restaurant_details.show'))}}" , 
          data   : {id  : id} ,
          //
          success : function(data) 
          {
            if(data.status == 1)
            {
              var request = data.data;
              $("#show-restaurant .modal-body").empty();
              
              
              
              var show_more_url = "{{url(route('restaurant.show' , ':id'))}}";
              show_more_url = show_more_url.replace(':id' , request.id);
              $("#show-restaurant .modal-title").text(request.name);
              $("#show-restaurant .modal-body").append(
                "<div style = 'width:40%; margin:auto;'><img style = 'border-radius:20px;' class = 'w-100' src = '" +  request.image_url +"'></div><br>" + 
                "<b><p>Email : </b>" + request.email + "</p>" +
                "<b><p>Phone : </b>" + request.phone + "</p>" +
                "<b><p>Minimum charge : </b>" + request.minimum_charge + "</p>" +
                "<b><p>Delivery Fees : </b>" + request.delivery_fees + "</p>" +
                "<b><p>Available ? : </b>" + (request.availability ? "yes" : "no") + "</p>" +
                "<b><p>Active ? : </b>" + (request.is_active ? "yes" : "no") + "</p>" +
                "<div class = 'text-center'><a target = '_Blank' href = '" + show_more_url + "' class = 'btn btn-primary'>Show More</a></div>"
              );
            }
            
          }
        });
      });


      $(".toggle-activate-button").click(function()
      {
        var button = $(this);
        var id = $(this).attr('id');
        var title = ($(this).text()=="Active" ? 'Deactivation' : 'Activation');
        var confirmButtonText = ($(this).text()=="Active" ? 'Deactive' : 'Active');
        var text = 'Are you sure you want to ' +  ($(this).text()=="Active" ? 'deactivte' : 'activate') + ' this restaurant ? ' ;

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
              url : "{{url(route('restaurant.toggle_activation'))}}" ,
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