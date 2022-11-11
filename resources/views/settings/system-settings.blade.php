@extends('layouts.main')

@section('title')
    System Settings
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">

            </div>
        </div>
    </div>
@endsection


@section('content')
    <section class="section">
        <div class="card">

            <form class="form" action="{{ url('set_settings') }}" method="post">
                @csrf
                <div class="card-body">
                    <div class="divider">
                        <h6 class="divider-text">Company Details</h6>
                    </div>
                    <div class="row mt-1">
                        <div class="card-body">


                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label text-center">Company Name</label>
                                <div class="col-sm-4">
                                    <input name="company_name" type="text" class="form-control"
                                        placeholder="Company Name"
                                        value="{{ system_setting('company_name') != '' ? system_setting('company_name') : '' }}"
                                        required="">
                                </div>

                                <label class="col-sm-2 col-form-label text-center">Website</label>
                                <div class="col-sm-4">
                                    <input name="company_website" type="text" class="form-control" placeholder="Website"
                                        value="{{ system_setting('company_website') != '' ? system_setting('company_website') : '' }}"
                                        >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label text-center">Email</label>
                                <div class="col-sm-4">
                                    <input name="company_email" type="email" class="form-control" placeholder="Email"
                                        value="{{ system_setting('company_email') != '' ? system_setting('company_email') : '' }}"
                                        required="">
                                </div>
                                <label class="col-sm-2 col-form-label text-center">Address</label>
                                <div class="col-sm-4">
                                    <textarea name="company_address" class="form-control" rows="3" placeholder="Enter Address">{{ system_setting('company_address') != '' ? system_setting('company_address') : '' }}</textarea>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label text-center">Telephone 1</label>
                                <div class="col-sm-4">
                                    <input name="company_tel1" type="text" class="form-control" placeholder="Telephone 1"
                                        pattern="\d*"
                                        value="{{ system_setting('company_tel1') != '' ? system_setting('company_tel1') : '' }}"
                                        required="">

                                </div>
                                <label class="col-sm-2 col-form-label text-center">Telephone 2</label>
                                <div class="col-sm-4">
                                    <input name="company_tel2" type="text" class="form-control" placeholder="Telephone 2"
                                        pattern="\d*"
                                        value="{{ system_setting('company_tel2') != '' ? system_setting('company_tel2') : '' }}"
                                        required="">

                                </div>

                            </div>

                        </div>




                    </div>


                    <div class="divider">
                        <h6 class="divider-text">More Setting</h6>

                    </div>
                    <div class="form-group row ">

                        <label class="col-sm-2 form-check-label text-center">Maintenance Mode</label>
                        <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                            <div class="form-check form-switch  text-center">

                                <input type="hidden" name="maintenance_mode" id="maintenance_mode"
                                    value="{{ system_setting('maintenance_mode') != '' ? system_setting('maintenance_mode') : 0 }}">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    {{ system_setting('maintenance_mode') == '1' ? 'checked' : '' }}
                                    id="switch_maintenance_mode">
                                <label class="form-check-label" for="switch_maintenance_mode"></label>
                            </div>
                        </div>
                        <label class="col-sm-2 form-check-label text-center">Currency Symbol</label>
                        <div class="col-sm-1">
                            <input name="currency_symbol" type="text" class="form-control" placeholder="Currency Symbol"
                                value="{{ system_setting('currency_symbol') != '' ? system_setting('currency_symbol') : '' }}"
                                required="">
                        </div>

                    </div>

                    <div class="divider">
                        <h6 class="divider-text">Notification FCM Key</h6>

                    </div>

                    <div class="form-group row ">

                        <label class="col-sm-2 form-check-label text-center">FCM Key</label>
                        <div class="col-sm-10 col-md-10 col-xs-12 text-center">
                            <textarea name="fcm_key" class="form-control" rows="3" placeholder="Fcm Key">{{ system_setting('fcm_key') != '' ? system_setting('fcm_key') : '' }}</textarea>

                        </div>


                    </div>

                    {{-- <div class="card-footer"> --}}
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" name="btnAdd" value="btnAdd"
                            class="btn btn-primary me-1 mb-1">Save</button>
                    </div>
                    {{-- </div> --}}
                 </form>
         </div>
        </div>

      
       
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        // $("#switch_maintenance_mode").change(function(){
        //     $(this).is(':checked') ? $(this).val(1) : $(this).val(0);
        // });

        $("#switch_maintenance_mode").on('change', function() {
            $("#switch_maintenance_mode").is(':checked') ? $("#maintenance_mode").val(1) : $("#maintenance_mode")
                .val(0);
        });
    </script>
@endsection
