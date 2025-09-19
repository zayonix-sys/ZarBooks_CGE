@extends('layout')

@section('content')

@push('head')
<link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
<link href="{{asset('lib/flatpickr/flatpickr.min.css')}}" rel="stylesheet">
@endpush

@push('footer')
<script src="{{asset('lib/datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('lib/datatables.net-responsive-bs4/dataTables.responsive.js')}}"></script>
<script src="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.js')}}"></script>
<script src="{{asset('lib/datatables/js/dataTableSum.js')}}"></script>
<script src="{{asset('lib/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('js/moment.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){

      const dateConfig = {
        //defaultDate: 'today',
        altInput: true,
        altFormat: 'd-M-Y',
        dateFormat: 'Y-m-d'
      }
      //Inatialize Start Date Picker
      $('#dpStartDate').flatpickr(dateConfig);

      //Inatialize End Date Picker
      $('#dpEndDate').flatpickr(dateConfig);


      var fyTbl = $('#fyDataTable').DataTable();

      //Showing Fiscal Year Data to Update
      fyTbl.on('click', '.editFY', function(){

        var $id = $(this).attr("data-id");

        $tr = $(this).closest('tr');
        var data = fyTbl.row($tr).data();

        var fyTitle = $tr.find("td:eq(0)").text().replace(/\s+/g, "");
        var startDate = moment(new Date(data[1])).format('YYYY-MM-DD');
        var endDate = moment(new Date(data[2])).format('YYYY-MM-DD');
        var status = $tr.find("td:eq(3)").text().replace(/\s+/g, "");

          console.log(status);
        $('#fyTitle').val(fyTitle);
        $('#fyEditStartDate').flatpickr().setDate(startDate);
        $('#fyEditEndDate').flatpickr().setDate(endDate);
        if(status == 'Active')
        {
          $('#fyActive').attr('checked', 'checked');
        }
        else
        {
          $('#fyInActive').attr('checked', 'checked');
        }

        $('#fyEditStartDate').flatpickr(dateConfig);
        $('#fyEditEndDate').flatpickr(dateConfig);
        // $('#fyTitle').val($tr.find("td:eq(0)").text());
        // $('#fyEditStartDate').val($tr.find("td:eq(1)").text());
        // $('#fyEditEndDate').val($tr.find("td:eq(2)").text());
        // $('#isActive').val($tr.find("td:eq(3)").text());

        //console.log(col3);

        $('#frmEditFY').attr('action', '/fiscalYear/'+$id);
        $('#fyModal').modal('show');

      });
    });
  </script>

  @endpush

  <div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url('{{asset('media/illustrations/corner-4.png')}}');">
    </div>
    <!--/.bg-holder-->
    <div class="card-body">
      <div class="row">
        <div class="col-lg-8">
          <h2 class="mb-0 font-weight-bold text-2xl text-warning">System Configuration</h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->

              {{ $link = "" }}
              @for($i = 1; $i <= count(Request::segments()); $i++)

              @if($i < count(Request::segments()) & $i > 0)
              <!-- {{ $link .= "/" . Request::segment($i) }} -->
              <li class="breadcrumb-item">
                <a href="<?= $link ?>">{{ ucwords(str_replace('-',' ',Request::segment($i)))}}</a>
              </li>
              <i class="fas fa-long-arrow-alt-right m-1"></i>

              @else {{ucwords(str_replace('-',' ',Request::segment($i)))}}
              @endif

              @endfor


            <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data</li> -->
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <!-- Tabs nav -->
          <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link mb-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill" href="#tbBusiness" role="tab" aria-controls="v-pills-home" aria-selected="true">
              <i class="fas fa-building fa-lg mr-2"></i>
              <span class="font-weight-bold text-uppercase">Business Information</span></a>

              <a class="nav-link mb-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill" href="#tbFiscalYear" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                <i class="fas fa-calendar-alt fa-lg mr-2"></i>
                <span class="font-weight-bold text-uppercase">Fiscal Year</span></a>

                <a class="nav-link mb-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                  <i class="fas fa-users fa-lg mr-2"></i>
                  <span class="font-weight-bold text-uppercase">User Management</span></a>

                  <a class="nav-link mb-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                    <i class="fas fa-check mr-2"></i>
                    <span class="font-weight-bold text-uppercase">Tax Management</span></a>
                  </div>
                </div>
              </div>

            </div>


            <div class="col-md-9">
              <!-- Tabs content -->
              <div class="tab-content" id="v-pills-tabContent">
                {{-- Business Registration Content --}}
                <div class="tab-pane fade shadow rounded bg-white show active p-5" id="tbBusiness" role="tabpanel" aria-labelledby="v-pills-home-tab">
                  <form>
                    <div class="form-row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Business Title</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Business Name" name="">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Business Logo</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input form-control" id="flLogo" accept="image/x-png,image/gif,image/jpeg,image/jpg" name="flLogo">
                            <label class="custom-file-label" for="flLogo" id="lblLogo">Upload Logo</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Business Address</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Street 1" name="">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>.</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Street 2" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm" placeholder="City" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm" placeholder="State/Provience" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm" placeholder="Country" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm" placeholder="Contact No." name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm" placeholder="Postal Code / Zip Code" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm" placeholder="Website" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>NTN Number</label>
                          <input type="text" class="form-control form-control-sm" placeholder="NTN" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>GST Number</label>
                          <input type="text" class="form-control form-control-sm" placeholder="GST" name="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>SST Number</label>
                          <input type="text" class="form-control form-control-sm" placeholder="SST" name="">
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <button type="submit" class="btn btn-block btn-outline-success fs--1">Add Business</button>

                      </div>
                      <div class="col-sm-2">
                        <button type="reset" class="btn btn-block btn-outline-warning fs--1">Cancel</button>
                      </div>
                    </div>
                  </form>
                </div>

                {{-- Fiscal Year Content --}}
                <div class="tab-pane fade shadow rounded bg-white p-5" id="tbFiscalYear" role="tabpanel" aria-labelledby="v-pills-profile-tab">

                  @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                  @endif

                  <form id="frmFY" method="post" action="/fiscalYear">
                    @csrf
                    <div class="form-row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>Fiscal Year Title</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Enter Title"
                          name="fy_title">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="datepicker">Start Date</label>
                          <input class="form-control datetimepicker flatpickr-input" id="dpStartDate" type="text"
                          name="fy_start_date" placeholder="Select Start Date">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="datepicker">End Date</label>
                          <input class="form-control datetimepicker flatpickr-input" id="dpEndDate" type="text"
                          name="fy_end_date" placeholder="Select End Date">
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <button type="submit" class="btn btn-block btn-outline-success fs--1" id="btnSaveFY">Add Fiscal Year</button>
                      </div>
                      <div class="col-sm-2">
                        <button type="reset" class="btn btn-block btn-outline-warning fs--1">Cancel</button>
                      </div>
                    </div>
                  </form>

                  <div class="row mt-3">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                          <div class="table-responsive fs--1">
                            <table id="fyDataTable" class="table table-striped border-bottom">
                              <thead class="bg-200 text-900">
                                <tr>
                                  <th class="border-0 ">Fiscal Year Title</th>
                                  <th class="border-0 ">Start Date</th>
                                  <th class="border-0 ">End Date</th>
                                  <th class="border-0 text-center">Status</th>
                                  <th class="border-0 text-center">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @forelse ($fiscalYears as $fiscalYear)
                                <tr>
                                  <td>
                                    {{ $fiscalYear->fy_title }}
                                  </td>
                                  <td >
                                    {{ date('d-M-Y', strtotime($fiscalYear->fy_start_date)) }}
                                  </td>
                                  <td>
                                    {{ date('d-M-Y', strtotime($fiscalYear->fy_end_date)) }}
                                  </td>
                                  <td class="text-center">
                                    @if ($fiscalYear->is_active == 1 )
                                      <span class="badge rounded-capsule badge-soft-success">Active
                                        <span class="ml-1 fas fa-check"></span>
                                      </span>
                                      @else
                                      <span class="badge rounded-capsule badge-soft-danger">In-Active
                                        <span class="ml-1 fas fa-times"></span>
                                      </span>
                                    @endif
                                  </td>
                                  <td class="text-center cursor-pointer">
                                    <span class="badge rounded-capsule badge-soft-success">
                                      <a data-id="{{ $fiscalYear->id }}" class="editFY">Edit
                                        <span class="ml-1 fas fa-edit fa-lg"></span>
                                      </a>
                                    </span>
                                   </td>
                                </tr>
                                @empty
                                <tr>
                                  <td colspan="4">
                                    No Record Found
                                  </td>
                                </tr>
                                @endforelse
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                  <h4 class="font-italic mb-4">Reviews</h4>
                  <p class="font-italic text-muted mb-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>

                <div class="tab-pane fade shadow rounded bg-white p-5" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                  <h4 class="font-italic mb-4">Confirm booking</h4>
                  <p class="font-italic text-muted mb-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                </div>
              </div>
            </div>
          </div>

<!-- Fiscal Year Edit Modal-->
<div class="modal fade" id="fyModal" tabindex="-1" role="dialog" aria-labelledby="fyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        	<h5 class="modal-title" id="fyModalLabel">Edit Fiscal Year</h5><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span class="font-weight-light" aria-hidden="true">&times;</span></button>
    	</div>
      <div class="modal-body">
      	<form method="POST" action="/fiscalYear" id="frmEditFY">
          @method('PUT')
          @csrf

          <div class="form-row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Fiscal Year Title</label>
                <input type="text" class="form-control form-control-sm" placeholder="Enter Title"
                name="fy_title" id="fyTitle">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="datepicker">Start Date</label>
                <input class="form-control datetimepicker flatpickr-input" id="fyEditStartDate" type="text"
                name="fy_start_date">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="datepicker">End Date</label>
                <input class="form-control datetimepicker flatpickr-input" id="fyEditEndDate" type="text"
                name="fy_end_date">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label for="customRadioInline1">Status</label>
                <div class="custom-control custom-radio custom-control-inline">
                  <input class="custom-control-input" id="fyActive" type="radio" name="is_active" value="1">
                  <label class="custom-control-label" for="fyActive">Active</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                  <input class="custom-control-input" id="fyInActive" type="radio" name="is_active" value="0">
                  <label class="custom-control-label" for="fyInActive">In-Active</label>
                </div>
              </div>
            </div>
            <div class="col-sm-12 my-auto text-center">
              <div class="form-group">
                <button type="submit" class="btn btn-outline-success ">Update</button>
              </div>
            </div>
        </form>
			</div>
    </div>
  </div>
</div>

@endsection
