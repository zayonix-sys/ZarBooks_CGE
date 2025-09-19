@extends('layout')
@section('title', 'Fiscal Year')

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
            $(document).ready(function () {

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

                //Showing Fiscal Year Data to Update
                var fyTbl = $('#fyDataTable').DataTable();
                fyTbl.on('click', '.editFY', function () {
                    var $id = $(this).attr("data-id");

                    $tr = $(this).closest('tr');
                    var data = fyTbl.row($tr).data();

                    var fyTitle = $tr.find("td:eq(0)").text().replace(/\s+/g, "");
                    var startDate = moment(new Date(data[1])).format('YYYY-MM-DD');
                    var endDate = moment(new Date(data[2])).format('YYYY-MM-DD');
                    var status = $tr.find("td:eq(3)").text().replace(/\s+/g, "");

                    //console.log(status);
                    $('#fyTitle').val(fyTitle);
                    $('#fyEditStartDate').flatpickr().setDate(startDate);
                    $('#fyEditEndDate').flatpickr().setDate(endDate);
                    if (status == 'Active') {
                        $('#fyActive').attr('checked', 'checked');
                    } else {
                        $('#fyInActive').attr('checked', 'checked');
                    }

                    $('#fyEditStartDate').flatpickr(dateConfig);
                    $('#fyEditEndDate').flatpickr(dateConfig);

                    $('#frmEditFY').attr('action', '/fiscalYear/' + $id);
                    $('#fyModal').modal('show');
                });
            });
        </script>

    @endpush

    <div class="card mb-3">
        <div class="bg-holder d-none d-lg-block bg-card"
             style="background-image:url('{{asset('media/illustrations/corner-4.png')}}');">
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
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
                                    <input class="form-control datetimepicker flatpickr-input" id="dpStartDate"
                                           type="text"
                                           name="fy_start_date" placeholder="Select Start Date">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="datepicker">End Date</label>
                                    <input class="form-control datetimepicker flatpickr-input" id="dpEndDate"
                                           type="text"
                                           name="fy_end_date" placeholder="Select End Date">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-block btn-outline-success fs--1" id="btnSaveFY">Add
                                    Fiscal Year
                                </button>
                            </div>
                            <div class="col-sm-6">
                                <button type="reset" class="btn btn-block btn-outline-warning fs--1">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
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
                                    <td>
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

    <!-- Fiscal Year Edit Modal-->
    <div class="modal fade" id="fyModal" tabindex="-1" role="dialog" aria-labelledby="fyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fyModalLabel">Edit Fiscal Year</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="frmEditFY">
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
                                    <input class="form-control datetimepicker flatpickr-input" id="fyEditStartDate"
                                           type="text"
                                           name="fy_start_date">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="datepicker">End Date</label>
                                    <input class="form-control datetimepicker flatpickr-input" id="fyEditEndDate"
                                           type="text"
                                           name="fy_end_date">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="customRadioInline1">Status</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" id="fyActive" type="radio" name="is_active"
                                               value="1">
                                        <label class="custom-control-label" for="fyActive">Active</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" id="fyInActive" type="radio"
                                               name="is_active" value="0">
                                        <label class="custom-control-label" for="fyInActive">In-Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 my-auto text-center">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-success ">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
