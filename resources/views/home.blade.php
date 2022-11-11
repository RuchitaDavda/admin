@extends('layouts.main')

@section('title') Home @endsection
@section('content')
        <section class="section">

                <div class="col-12 col-lg-12">
                    <div class="row">

                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('customer') }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-12">
                                                <h6 class="font-semibold text-uppercase">Total Customer</h6>
                                                <label><b>{{ isset($list['total_customer']) ? $list['total_customer'] : 0;  }} </b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('customer') }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-12">
                                            <h6 class="font-semibold text-uppercase">Total Property</h6>
                                            <label><b>{{ isset($list['total_property']) ? $list['total_property'] : 0;  }} </b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                         <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property')."?status=1" }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <h6 class="font-semibold text-uppercase">Total Active Property</h6>
                                            <label><b>{{ isset($list['total_active_property']) ? $list['total_active_property'] : 0;  }} </b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property')."?status=0" }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-12">
                                                <h6 class="font-semibold text-uppercase">Total Deactive Property</h6>
                                                <label><b>{{ isset($list['total_inactive_property']) ? $list['total_inactive_property'] : 0; }} </b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>



                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property-inquiry') }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-12">
                                            <h6 class="font-semibold text-uppercase">Total Inquiry</h6>
                                            <label><b>{{ isset($list['total_property_inquiry']) ? $list['total_property_inquiry'] : 0;  }} </b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                         <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property-inquiry')."?status=0" }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <h6 class="font-semibold text-uppercase">Total Inquiry Pending</h6>
                                            <label><b>{{ isset($list['total_property_inquiry_pending']) ? $list['total_property_inquiry_pending'] : 0;  }} </b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property-inquiry')."?status=1" }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-12">
                                            <h6 class="font-semibold text-uppercase">Total Inquiry Accept</h6>
                                            <label><b>{{ isset($list['total_property_inquiry_accept']) ? $list['total_property_inquiry_accept'] : 0; }} </b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        
                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property-inquiry')."?status=3" }}">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-12">
                                            <h6 class="font-semibold text-uppercase">Total Inquiry Complete</h6>
                                            <label><b>{{ isset($list['total_property_inquiry_complete']) ? $list['total_property_inquiry_complete'] : 0; }} </b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-12 col-lg-3 col-md-3">
                            <a href="{{ url('property-inquiry')."?status=4" }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-12">
                                                <h6 class="font-semibold text-uppercase">Total Inquiry Cancle</h6>
                                                <label><b>{{ isset($list['total_property_inquiry_cancle']) ? $list['total_property_inquiry_cancle'] : 0; }} </b></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>


        </section>
@endsection
