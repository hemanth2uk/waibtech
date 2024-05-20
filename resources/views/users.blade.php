@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Users
        <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                Add User
              </button>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th>S/no</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Created Date</th>
                 
                </tr>
                </thead>
                <tbody id="dynamicTable">
           
                
                </tbody>
              
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <div class="modal fade in" id="modal-default" >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add User</h4>
              </div>
              <div class="modal-body">
            
              <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="name" placeholder="Enter Name">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Email </label>
                  <input type="email" class="form-control" id="email" placeholder="Enter email">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveUSr()">Save changes</button>

              </div>
            </div>
          </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  
          function saveUSr()
          {  
            // Get form data
            var name = $('#name').val();
            var email = $('#email').val();
    
            
            // Submit form data via AJAX
            $.ajax({
                type: 'POST',
                url: "{{ route('save.users') }}", // Replace with your server-side script URL
                data: {name:name,email:email,_token:"{{ csrf_token() }}"},
                success: function(response){
                    // Handle success response
                var parsedData = JSON.parse(response)
                if(parsedData.status==200)
                {
                  alert(parsedData.msg);
                  listUsers();
                }
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
          }
    listUsers();
    function listUsers()
    {
      $.ajax({
                type: 'GET',
                url: "{{ route('users.list')}}", // Replace with your server-side script URL
                success: function(response){
                    // Handle success response
            $('#dynamicTable').html(response);
            $('#modal-default').modal('hide');
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
    }
</script>

  @endsection