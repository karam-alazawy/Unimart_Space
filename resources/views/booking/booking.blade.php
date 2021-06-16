@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Booking',
    'activePage' => 'Booking',
    'activeNav' => '',
])

@section('content')
<body class="sidebar-mini clickup-chrome-ext_installed">
 <!-- Navbar -->

  <!-- End Navbar --> <div class="panel-header">
  </div>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
              <a class="btn btn-primary btn-round text-white pull-right" href="{{ route('booking.add') }}">Add Booking</a>
            <h4 class="card-title">Booking</h4>
            <div class="col-12 mt-2">
            <form method="post" action="{{ route('booking.index') }}" autocomplete="off"
            enctype="multipart/form-data">
              @csrf
              @method('get')
              @include('alerts.success')
            <input type="text" class="form-control" name='q' placeholder="email">
            </form>
                                        </div>
          </div>
          <div class="card-body">
            <div class="toolbar">
              <!--        Here you can write extra buttons/actions for the toolbar              -->
            </div>
            <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
              <tr>
                  <th>#</th>
                  <th>Name</th> 
                  <th>email</th> 
                  <th>Office</th>

                  <th>Room</th>
                  <th>Table</th>

                  <th>From</th>
                  <th>To</th>
                  <th>Creation date</th>
                  <th>Status</th>

                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>email</th>
                  <th>Office</th>
                  <th>Room</th>
                  <th>Table</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Creation date</th>
                  <th>Status</th>
                </tr>
              </tfoot>
              <tbody>
              @foreach($reservations as $key => $data)

                                  <tr>
                    <td>
                      <span class="avatar avatar-sm rounded-circle">
                        <!-- <img src="{{asset('assets')}}/img/default-avatar.png" alt="" style="max-width: 80px; border-radiu: 100px"> -->
                      </span>
                    </td>
                    <td>{{$data->customerRes->name}}</td>
                    <td>{{$data->customerRes->email}}</td>
                    <td>{{$data->roomRes2->officeFromRoom->office_name}}</td>

                    <td>{{$data->roomRes->room_name}}</td>
                    <td>{{$data->tableRes->tableLang->table_name}}</td>
                    <td>{{$data->date_in}}</td>
                    <td>{{$data->date_out}}</td>
                    <td>{{$data->created_at}}</td>
                  

                    <?php switch ($data->status) {
                        case 'pending':
                            echo '<td  class="">
                            <a type="button" href="'. route("booking.approve",[$data->id]) .'" rel="tooltip" class="btn btn-success btn-icon btn-sm " data-original-title="" title="">
                              <i class="now-ui-icons ui-2_settings-90"></i>
                            </a>
                            <a type="button"  href="'. route("booking.cancel",[$data->id]) .'" rel="tooltip" class="btn btn-danger btn-icon btn-sm " data-original-title="" title="">
                            <i class="now-ui-icons media-1_button-power"></i>
                            </td>';
                             break;
                        
                             case 'cancel':
                                echo ' <td  style="color:red">Canceld</td>';
                                 break;
                             
                                 case 'approve':
                                    echo ' <td style="color:green">Approved</td>';
                                     break;
                                 
                        default:
                            # code...
                            break;
                    } ?>
                   
                  
                  </tr>@endforeach

                              </tbody>
            </table>
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>

    <!-- end row -->
  </div>
    <footer class="footer">

</footer></div>                    </div>
  <!--   Core JS Files   -->
  <script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
  <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
  <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
  <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="{{ asset('assets') }}/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('assets') }}/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('assets') }}/js/now-ui-dashboard.min.js?v=1.3.0" type="text/javascript"></script>
  <!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('assets') }}/demo/demo.js"></script>
  @stack('js')
</body>
@endsection

</html>