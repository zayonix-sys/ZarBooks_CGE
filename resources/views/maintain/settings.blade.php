@extends('layout')
@section('title', 'Settings')

@section('content')

    @push('head')
        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">
    @endpush

    @push('footer')
        <script src="{{asset('lib/select2/select2.min.js')}}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                //Select Focus on Search Filed
                $(document).on('select2:open', () => {
                    document.querySelector('input.select2-search__field').focus();
                });

                // Select Open on Down Key
                $(document).on('keydown', '.select2', function (e) {
                    if (e.originalEvent && e.which == 40) {
                        e.preventDefault();
                        $(this).siblings('select').select2('open');
                    }
                });

                $('select').select2({
                    theme: 'bootstrap4',
                    selectOnClose: true
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

                                @else
                                    {{ucwords(str_replace('-',' ',Request::segment($i)))}}
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

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tabs nav -->
                    <div class="nav nav-pills nav-fill" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link mx-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill"
                           href="#tbVouchers" role="tab" aria-controls="v-pills-home" aria-selected="true">
                            <i class="fas fa-dollar-sign fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Cash/Bank Book Account</span></a>

                        {{--                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill"--}}
                        {{--                           href="#tbLiabilities" role="tab" aria-controls="v-pills-profile" aria-selected="false">--}}
                        {{--                            <i class="fab fa-amazon-pay fa-lg mr-2"></i>--}}
                        {{--                            <span class="font-weight-bold text-uppercase">Liabilities</span></a>--}}

                        {{--                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill"--}}
                        {{--                           href="#tbEquities" role="tab" aria-controls="v-pills-messages" aria-selected="false">--}}
                        {{--                            <i class="fas fa-crown fa-lg mr-2"></i>--}}
                        {{--                            <span class="font-weight-bold text-uppercase">Equities</span></a>--}}

                        {{--                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"--}}
                        {{--                           href="#tbRevenues" role="tab" aria-controls="v-pills-settings" aria-selected="false">--}}
                        {{--                            <i class="fas fa-file-invoice-dollar fa-lg mr-2"></i>--}}
                        {{--                            <span class="font-weight-bold text-uppercase">Revenues</span></a>--}}

                        {{--                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"--}}
                        {{--                           href="#tbExpenses" role="tab" aria-controls="v-pills-settings" aria-selected="false">--}}
                        {{--                            <i class="fab fa-buromobelexperte fa-lg mr-2"></i>--}}
                        {{--                            <span class="font-weight-bold text-uppercase">Expenses</span></a>--}}
                    </div>

                    <!-- Tabs content -->
                    <div class="tab-content mx-3 my-3" id="v-pills-tabContent">

                        {{-- Voucher Settings --}}
                        <div class="tab-pane fade show active p-3" id="tbVouchers"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action=" {{ route('storeCashPaymentAccount') }}" method="post">
                                                @csrf
                                                <div class="form-row ">
                                                    <div class="col-sm-12">
                                                        <h2 class="mb-0 font-weight-bold">Select Cash and Bank Book
                                                            Account</h2>
                                                        <br>
                                                        @foreach($parentAccounts as $assetsAccount)
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input"
                                                                       id="{{ 'chkDr'.$loop->index }}" type="checkbox"
                                                                       value="{{ $assetsAccount->id }}"
                                                                       name="cash_payment[]">
                                                                <label class="custom-control-label"
                                                                       for="{{ 'chkDr'.$loop->index }}">{{ $assetsAccount->title }}</label>
                                                            </div>
                                                        @endforeach
                                                        <br>
                                                        <div class="my-auto">
                                                            <button type="submit" class="btn btn-outline-success"
                                                                    id="btnSaveDr">Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="card">--}}
{{--                                        <div class="card-body">--}}
{{--                                            <form action=" {{ route('storeBankPaymentAccount') }}" method="post">--}}
{{--                                                @csrf--}}
{{--                                                <div class="form-row ">--}}
{{--                                                    <div class="col-sm-12">--}}
{{--                                                        <h2 class="mb-0 font-weight-bold">Select Bank Payment--}}
{{--                                                            Accounts</h2>--}}
{{--                                                        <br>--}}
{{--                                                        @foreach($parentAccounts as $assetsAccount)--}}
{{--                                                            <div class="custom-control custom-checkbox">--}}
{{--                                                                <input class="custom-control-input"--}}
{{--                                                                       id="{{ 'chkCr'.$loop->index }}" type="checkbox"--}}
{{--                                                                       value="{{ $assetsAccount->id }}"--}}
{{--                                                                       name="bank_payment[]">--}}
{{--                                                                <label class="custom-control-label"--}}
{{--                                                                       for="{{ 'chkCr'.$loop->index }}">{{ $assetsAccount->title }}</label>--}}
{{--                                                            </div>--}}
{{--                                                        @endforeach--}}
{{--                                                        <br>--}}
{{--                                                        <div class="my-auto">--}}
{{--                                                            <button type="submit" class="btn btn-outline-success"--}}
{{--                                                                    id="btnSaveCr">Save--}}
{{--                                                            </button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
