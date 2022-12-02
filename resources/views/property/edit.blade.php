@extends('layouts.main')

@section('title')
    Edit Property
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
                            Edit
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


            {!! Form::open([
                'route' => ['property.update', $id],
                'method' => 'PATCH',
                'data-parsley-validate',
                'files' => true,
            ]) !!}
            <div class="card-body">

                <div class="row ">


                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('title', 'Title', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('title', isset($list->title) ? $list->title : '', ['class' => 'form-control ', 'placeholder' => 'Title', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                    </div>
                    <div class="col-md-4 text-center form-group  mandatory">
                        {{ Form::label('', 'Propery Type', ['class' => 'form-label col-12 text-center']) }}

                        <div class="control-label col-form-label form-check form-check-inline mb-2">
                            <input type="radio" name="property_type" value="0"
                                {{ isset($list->propery_type) && $list->propery_type == 0 ? 'checked' : '' }}
                                class="form-check-input" id="property_type">
                            {{ Form::label('property_type', 'Sell', ['class' => 'form-check-label']) }}
                        </div>
                        <div class="control-label col-form-label form-check form-check-inline mb-2">

                            <input type="radio" name="property_type" value="1"
                                {{ isset($list->propery_type) && $list->propery_type == 1 ? 'checked' : '' }}
                                class="form-check-input" id="property_type">
                            {{ Form::label('property_type', 'Rent', ['class' => 'form-check-label']) }}
                        </div>
                    </div>
                    <div class="col-md-2 form-group  mandatory">
                        {{ Form::label('price', 'Price', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('price', isset($list->price) ? $list->price : '', ['class' => 'form-control ', 'placeholder' => 'Price', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                    </div>

                </div>
                <div class="row ">


                    <div class="col-md-12 col-12 ">

                        <div class="divider">
                            <div class="divider-text">Address</div>
                        </div>
                        <div class="row mt-10">



                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('district', 'District', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('Kutch', 'Kutch', ['class' => 'form-control ', 'placeholder' => 'District', 'readonly' => 'true', 'id' => 'district']) }}
                                <input type="hidden" name="State" value="Gujarat">
                            </div>

                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('taluka', 'Taluka', ['class' => 'form-label col-12 text-center']) }}
                                <select name="taluka" id="taluka" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($taluka as $row)
                                        <option value="{{ $row }}"
                                            {{ strval($list->taluka) == strval($row) ? ' selected=selected' : '' }}>
                                            {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('village', 'Village Or City', ['class' => 'form-label col-12 text-center']) }}
                                <select name="village" id="village" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($village as $row)
                                        <option value="{{ $row }}"
                                            {{ strval($list->village) == strval($row) ? ' selected=selected' : '' }}>
                                            {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group  mandatory">
                                {{ Form::label('latitude', 'Latitude', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('latitude', isset($list->latitude) ? $list->latitude : '', ['class' => 'form-control ', 'placeholder' => 'Latitude', 'data-parsley-required' => 'true', 'id' => 'latitude']) }}

                            </div>
                            <div class="col-md-2 form-group  mandatory">
                                {{ Form::label('longitude', 'Longitude', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('longitude', isset($list->longitude) ? $list->longitude : '', ['class' => 'form-control ', 'placeholder' => 'Longitude', 'data-parsley-required' => 'true', 'id' => 'longitude']) }}

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
                            <option value=""> Select Option </option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}"
                                    {{ $list->category_id == $row->id ? ' selected=selected' : '' }}
                                    data-parametertypes='{{ $row->parameter_types }}'> {{ $row->category }}
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
                                <option value="{{ $key }}"
                                    {{ $list->unit_type == $key ? ' selected=selected' : '' }}>
                                    {{ $value }} </option>
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
                            @foreach ($parameters as $res)
                                @foreach ($par_arr as $key => $arr)
                                    @if ($key == $res->name)
                                        <div class="col-md-2 form-group  mandatory">

                                            {{ Form::label($res->name, $res->name, ['class' => 'form-label text-center col-12']) }}
                                            {{ Form::text($res->id, $arr, ['data-parsley-required' => 'true', 'id' => $res->id, 'class' => 'form-control mt-3']) }}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach


                        </div>





                    </div>
                    <hr class=" mt-5">


                    <div class="row mt-4">

                        <div class="col-md-6 col-12 form-group">
                            {{ Form::label('address', 'Client Address', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::textarea('client_address', isset($list->client_address) ? $list->client_address : '', ['class' => 'form-control', 'placeholder' => 'Client Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off']) }}
                        </div>

                        <div class="col-md-6 col-12 form-group">
                            {{ Form::label('address', 'Address', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::textarea('address', isset($list->address) ? $list->address : '', ['class' => 'form-control ', 'placeholder' => 'Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off']) }}
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
                            <input type="hidden" value="{{ $list->title_image }}" name="old_title_image">

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
                        <div class="col-md-12 col-12 form-group">

                            <div class="row">
                                <?php $i = 0; ?>
                                @if (!empty($list->gallery))
                                    @foreach ($list->gallery as $row)
                                        <div class="col-xs-2 col-sm-3 col-md-6 col-lg-2  mt-5" data-kt-image-input="true"
                                            id='{{ $row->id }}'>
                                            <a class="image-popup-no-margins"
                                                href="{{ url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH') . $list->id . '/' . $row->image }}">
                                                <img class="rounded  shadow img-fluid" alt=""
                                                    src="{{ url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH') . $list->id . '/' . $row->image }}"
                                                    style="height: 144px; width:100%"></a>
                                            </a>


                                            <a data-rowid="{{ $row->id }}"
                                                class="btn btn-danger btn-block mt-2 RemoveBtn">{{ __('Remove') }}</a>

                                        </div>


                                        <?php $i++; ?>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 col-12 form-group mandatory">
                            {{ Form::label('description', 'Description', ['class' => 'form-label col-12 text-center']) }}


                            {{ Form::textarea('description', isset($list->description) ? $list->description : '', ['class' => 'form-control ', 'rows' => '2', 'id' => 'tinymce_editor', 'data-parsley-required' => 'true']) }}

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
            $("#category").change(function() {


                // console.log("click");

                $('#parameter_type').empty();


                var parameter_types = $(this).find(':selected').data('parametertypes');


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
                            });
                        });
                    }
                });
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


    <script>
        $(".RemoveBtn").click(function() {

            var id = $(this).data('rowid');
            Swal.fire({
                title: 'Are You Sure Want to Remove This Image',
                icon: 'error',
                showDenyButton: true,

                confirmButtonText: 'Yes',
                denyCanceButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('property.removeGalleryImage') }}",

                        type: "POST",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {

                            if (response.error == false) {
                                Toastify({
                                    text: 'Image Delete Successful',
                                    duration: 6000,
                                    close: !0,
                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                                }).showToast();
                                $("#" + id).html('');
                            } else if (response.error == true) {
                                Toastify({
                                    text: 'Something Wrong !!!',
                                    duration: 6000,
                                    close: !0,
                                    backgroundColor: '#dc3545' //"linear-gradient(to right, #dc3545, #96c93d)"
                                }).showToast()
                            }
                        },
                        error: function(xhr) {}
                    });
                }
            })


        });
    </script>
@endsection
