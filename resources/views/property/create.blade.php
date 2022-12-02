@extends('layouts.main')

@section('title')
    Add Property
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('property.index') }}" id="subURL">View Property</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Add
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <section class="section">
        <div class="card">


            {!! Form::open(['route' => 'property.store', 'data-parsley-validate', 'files' => true]) !!}
            <div class="card-body">

                <div class="row ">


                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('title', 'Title', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('title', '', ['class' => 'form-control ', 'placeholder' => 'Title', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                    </div>

                    <div class="col-md-4 col-12 text-center form-group  mandatory">
                        {{ Form::label('', 'Propery Type', ['class' => 'form-label col-12 text-center']) }}

                        <div class="control-label col-form-label form-check form-check-inline mb-2">

                            {{ Form::radio('property_type', 0, null, ['class' => 'form-check-input', 'id' => 'property_type', 'required' => true]) }}
                            {{ Form::label('property_type', 'Sell', ['class' => 'form-check-label']) }}


                        </div>
                        <div class="control-label col-form-label form-check form-check-inline mb-2">
                            {{ Form::radio('property_type', 1, null, ['class' => 'form-check-input', 'id' => 'property_type', 'required' => true]) }}
                            {{ Form::label('property_type', 'Rent', ['class' => 'form-check-label']) }}

                        </div>
                    </div>
                    <div class="col-md-2 form-group  mandatory">
                        {{ Form::label('price', 'Price', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('price', '', ['class' => 'form-control ', 'placeholder' => 'Price', 'data-parsley-required' => 'true', 'id' => 'price']) }}

                    </div>



                </div>
                <div class="row">
                    <div class="col-md-12 col-12 ">

                        <div class="divider">
                            <div class="divider-text">Address</div>
                        </div>
                        <div class="row mt-10">

                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('district', 'District', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('district', 'Kutch', ['class' => 'form-control ', 'placeholder' => 'District', 'readonly' => 'true', 'id' => 'district']) }}
                                <input type="hidden" name="state" value="Gujarat">
                            </div>

                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('taluka', 'Taluka', ['class' => 'form-label col-12 text-center']) }}
                                <select name="taluka" id="taluka" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($taluka as $row)
                                        <option value="{{ $row }}"> {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('village', 'Village Or City', ['class' => 'form-label col-12 text-center']) }}
                                <select name="village" id="village" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>

                                </select>
                            </div>
                            <div class="col-md-2 form-group  mandatory">
                                {{ Form::label('latitude', 'Latitude', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('latitude', '', ['class' => 'form-control ', 'placeholder' => 'Latitude', 'data-parsley-required' => 'true', 'id' => 'latitude']) }}

                            </div>
                            <div class="col-md-2 form-group  mandatory">
                                {{ Form::label('longitude', 'Longitude', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('longitude', '', ['class' => 'form-control ', 'placeholder' => 'Longitude', 'data-parsley-required' => 'true', 'id' => 'longitude']) }}

                            </div>
                        </div>
                        <hr>
                    </div>
                </div>


                <div class="row  mt-4">



                    <div class="col-md-6 col-12 form-group">
                        {{ Form::label('category', 'Category', ['class' => 'form-label col-12 text-center']) }}
                        <select name="category" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                            id="category" required='true'>
                            <option value="      "> Select Option </option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}" data-parametertypes='{{ $row->parameter_types }}'>
                                    {{ $row->category }}
                                </option>
                            @endforeach
                        </select>
                    </div>




                    <div class="col-md-6 col-12 form-group mandatory">
                        {{ Form::label('unit_type', 'Unit Type', ['class' => 'form-label col-12 text-center']) }}

                        <select name="unit_type" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                            id="unit_type" required='true'>
                            <option value=""> Select Option </option>
                            @foreach ($unittype as $key => $value)
                                <option value="{{ $key }}"> {{ $value }} </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="divider">
                        <div class="divider-text unit">Unit Value</div>
                    </div>

                    <div class="row  mt-4" id="parameters">
                        {{ Form::hidden('category_count[]', $category, ['id' => 'category_count']) }}
                        {{ Form::hidden('parameter_count[]', $parameters, ['id' => 'parameter_count']) }}
                        {{ Form::hidden('parameter_add', '', ['id' => 'parameter_add']) }}


                        <div id="parameter_type" name=parameter_type class="row">

                        </div>
                    </div>
                    <hr class=" mt-5">
                    <div class="row">

                        <div class="col-md-6 col-12 form-group">
                            {{ Form::label('address', 'Client Address', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::textarea('client_address', '', ['class' => 'form-control ', 'placeholder' => 'Client Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off']) }}
                        </div>

                        <div class="col-md-6 col-12 form-group">
                            {{ Form::label('address', 'Address', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::textarea('address', '', ['class' => 'form-control ', 'placeholder' => 'Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off']) }}
                        </div>


                    </div>

                    <div class="row mt-4">

                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('title_image', 'Image', ['class' => 'col-12 form-label text-center']) }}

                            {{ Form::file('title_image', ['class' => 'form-control']) }}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif

                        </div>

                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('gallery_images', 'Gallery Images', ['class' => 'form-label col-12 text-center']) }}

                            {{ Form::file('gallery_images[]', ['class' => 'form-control', 'multiple' => true]) }}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 col-12 form-group mandatory">
                            {{ Form::label('description', 'Description', ['class' => 'form-label col-12 text-center']) }}


                            {{ Form::textarea('description', '', ['class' => 'form-control ', 'rows' => '2', 'id' => 'tinymce_editor', 'data-parsley-required' => 'true']) }}

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-12 d-flex justify-content-end">

                        {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
    </section>
@endsection

@section('script')
    <script>
        $('#unit_type').change(function() {
            $('.unit').empty();
            $('.unit').append('Unit Type (' + $('#unit_type :selected').text() + ')');

        });
        $(function() {
            // $('#parameters').append($('<div id="1">abcs</div>'));

            $("#category").change(function() {
                $('#parameter_type').empty();
                console.log($('#parameter_type').children('#ABC').text());

                var parameter_types = $(this).find(':selected').data('parametertypes');


                console.log($('#parameter_count').val());
                parameter_data = $.parseJSON($('#parameter_count').val());

                arr = [];
                data = $.parseJSON($('#category_count').val());
                $.each(data, function(key, value) {
                    if (value.parameter_types == parameter_types) {

                        $.each(parameter_data, function(key, name) {

                            $.each((parameter_types + ',').split(","), function(key,
                                value) {
                                if (value == name.id) {
                                    console.log(name.name);


                                    $('#parameter_type').append($(



                                        '<div class="form-group mandatory col-md-3"><label for="' +
                                        name.name +
                                        '" class="form-label text-center col-12">' +
                                        name.name +
                                        '</label><input class="form-control" data-parsley-required="true" type="text" id="' +
                                        name.id + '" name="' + name.id +
                                        '"></div>'


                                    ));
                                }
                            })
                        })
                    }

                })


            });


            $("#taluka").change(function() {
                var taluka = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('property.getVillageByTaluka') }}",
                    dataType: 'json',
                    data: {
                        taluka: taluka
                    },

                    success: function(response) {
                        $('#village').empty();
                        if (response.error == false) {
                            $.each(response.data, function(i, item) {
                                $('#village').append($('<option>', {
                                    value: item,
                                    text: item

                                }));
                            });
                        } else {
                            $('#village').empty();
                        }
                    }
                });

            });
        });
    </script>
@endsection
