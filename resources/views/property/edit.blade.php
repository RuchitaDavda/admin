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


            {!! Form::open(['route' => ['property.update',$id],'method' => 'PATCH', 'data-parsley-validate', 'files' => true]) !!}
            <div class="card-body">

                <div class="row ">
                    <div class="col-md-6 col-12 ">
                        
                            <div class="col-md-12 col-12 form-group mandatory">

                                {{ Form::label('title', 'Title', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('title', isset($list->title) ? $list->title : '', ['class' => 'form-control ', 'placeholder' => 'Title', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                            </div>
                           
                       
                    </div>

                    <div class="col-md-6 col-12 ">
                        <div class="row "> 
                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('category', 'Category', ['class' => 'form-label col-12 text-center']) }}
                                <select name="category" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                                    id="category" required='true'>
                                    <option value=""> Select Option </option>
                                    @foreach ($category as $row)
                                        <option value="{{ $row->id }}" {{ $list->category_id == $row->id ? ' selected=selected' : '' }} data-parametertypes='{{ $row->parameter_types  }}'> {{ $row->category }} </option>
                                    @endforeach
                                </select>
                               
                            </div>
                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('district', 'District', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('Kutch', 'Kutch', ['class' => 'form-control ', 'placeholder' => 'District', 'readonly' => 'true', 'id' => 'district']) }}
                                <input type="hidden" name="State" value="Gujarat">
                            </div>

                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('taluka', 'Taluka', ['class' => 'form-label col-12 text-center']) }}
                                <select name="taluka" id="taluka" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($taluka as $row)
                                        <option value="{{ $row }}" {{ (strval($list->taluka) == strval($row)) ? ' selected=selected' : '' }}> {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('village', 'Village Or City', ['class' => 'form-label col-12 text-center']) }}
                                <select name="village" id="village" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($village as $row)
                                        <option value="{{ $row }}" {{ (strval($list->village) == strval($row)) ? ' selected=selected' : '' }}> {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


               


                <div class="row  mt-4">
                    <div class="col-md-6 col-12 ">
                        <div class="row ">
                            <div class="col-md-6 text-center form-group  mandatory">
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

                            <div class="col-md-6 form-group  mandatory">
                                {{ Form::label('price', 'Price', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('price', isset($list->price) ? $list->price : '', ['class' => 'form-control ', 'placeholder' => 'Price', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                            </div>
                        </div>

                    </div>
                    


                    <div class="col-md-6 col-12 ">
                        <div class="row ">
                            <div class="col-md-6 col-12 form-group mandatory">
                                {{ Form::label('unit_type', 'Unit Type', ['class' => 'form-label col-12 text-center']) }}

                                <select name="unit_type" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                                    id="unit_type" required='true'>
                                    <option value=""> Select Option </option>
                                    @foreach ($unittype as $key => $value)
                                            <option value="{{ $key }}"  {{ $list->unit_type == $key ? ' selected=selected' : '' }}> {{ $value }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group  mandatory">
                                {{ Form::label('latitude', 'Latitude', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('latitude', isset($list->latitude) ? $list->latitude : '', ['class' => 'form-control ', 'placeholder' => 'Latitude', 'data-parsley-required' => 'true', 'id' => 'latitude']) }}

                            </div>
                            <div class="col-md-3 form-group  mandatory">
                                {{ Form::label('longitude', 'Longitude', ['class' => 'form-label col-12 text-center']) }}
                                {{ Form::text('longitude', isset($list->longitude) ? $list->longitude : '', ['class' => 'form-control ', 'placeholder' => 'Longitude', 'data-parsley-required' => 'true', 'id' => 'longitude']) }}

                            </div>
                        </div>
                    </div>
                </div>
                 
                
                <div class="divider">
                    <div class="divider-text">Unit Value</div>
                </div>

                <div class="row  mt-4">
                    <div class="col-md-3 col-12 mt-2 mb-2 div_carpet_area" <?= ($list->carpet_area == '') ? 'style="display: none;"' : ''  ?>>
                        {{ Form::label('carpet_area', 'Carpet Area', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('carpet_area', isset($list->carpet_area) ? $list->carpet_area : '', ['class' => 'form-control ', 'placeholder' => 'Carpet Area', 'id' => 'carpet_area']) }}

                    </div>

                    <div class="col-md-3 col-12 mt-2 mb-2 div_build_up_area"  <?= ($list->built_up_area == '') ? 'style="display: none;"' : ''  ?>>
                        {{ Form::label('build_up_area', 'Built-Up Area', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('built_up_area',  isset($list->built_up_area) ? $list->built_up_area : '', ['class' => 'form-control ', 'placeholder' => 'Built-Up Area', 'id' => 'build_up_area']) }}

                    </div>
                    <div class="col-md-3 col-12 mt-2 mb-2 div_plot_area"  <?= ($list->plot_area == '') ? 'style="display: none;"' : ''  ?>>
                        {{ Form::label('plot_area', 'Plot Area', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('plot_area',isset($list->plot_area) ? $list->plot_area : '', ['class' => 'form-control ', 'placeholder' => 'Plot Area', 'id' => 'plot_area']) }}

                    </div>
                   
                    <div class="col-md-3 col-12 mt-2 mb-2 div_hecta_area"  <?= ($list->hecta_area == '') ? 'style="display: none;"' : ''  ?> >
                        {{ Form::label('hecta_area', 'Hecta Area', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('hecta_area', isset($list->hecta_area) ? $list->hecta_area : '', ['class' => 'form-control ', 'placeholder' => 'Hecta Area', 'id' => 'hecta_area']) }}

                    </div>

                    <div class="col-md-3 col-12 mt-2 mb-2 div_acre" <?= ($list->acre == '') ? 'style="display: none;"' : ''  ?>>
                        {{ Form::label('acre', 'Acre', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('acre', isset($list->acre) ? $list->acre : '', ['class' => 'form-control ', 'placeholder' => 'Acre', 'id' => 'acre']) }}

                    </div>


                    <div class="col-md-3 col-12 mt-2 mb-2 div_house_type"  <?= ($list->house_type == '') ? 'style="display: none;"' : ''  ?>>
                        {{ Form::label('house_type', 'House Type', ['class' => 'form-label col-12 text-center']) }}
                        <select name="house_type" class="select2 form-select form-control-sm">
                            <option value=""> Select Option </option>
                            @foreach ($housetype as $row)
                                 <option value="{{ $row->id }}" {{ $list->house_type == $row->id ? ' selected=selected' : '' }}> {{ $row->type }} </option>
                            @endforeach
                        </select>    

                    </div>

                    <div class="col-md-3 col-12 mt-2 mb-2 div_furnished"  <?= ($list->furnished == '') ? 'style="display: none;"' : ''  ?>>
                          {{ Form::label('furnished', 'Furnished', ['class' => 'form-label col-12 text-center']) }}
                          {{ Form::select('furnished', ['' => 'Select Option','0' => 'Furnished', '1' => 'Semi-Furnished', '3' => 'Not-Furnished'], isset($list->furnished) ? $list->furnished : '', ['class' => 'select2 form-select form-control-sm', 'data-parsley-minSelect' => '1', 'id' => 'furnished']) }}


                    </div>


                    <div class="col-md-3 col-12 mt-2 mb-2 div_house_no <?= ($list->house_no == '') ? 'style="display: none;"' : ''  ?>">
                        {{ Form::label('house_no', 'House No', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('house_no', isset($list->house_no) ? $list->house_no : '', ['class' => 'form-control ', 'placeholder' => 'House No', 'id' => 'house_no']) }}

                    </div>

                    <div class="col-md-3 col-12 mt-2 mb-2 div_survey_no <?= ($list->survey_no == '') ? 'style="display: none;"' : ''  ?>" >
                        {{ Form::label('survey_no', 'Survey No', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('survey_no', isset($list->survey_no) ? $list->survey_no : '', ['class' => 'form-control ', 'placeholder' => 'Survey No', 'id' => 'survey_no']) }}
                    </div>

                    <div class="col-md-3 col-12 mt-2 mb-2 div_plot_no <?= ($list->plot_no == '') ? 'style="display: none;"' : ''  ?>" >
                        {{ Form::label('plot_no', 'Plot No', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('plot_no', isset($list->plot_no) ? $list->plot_no : '', ['class' => 'form-control ', 'placeholder' => 'Plot No', 'id' => 'plot_no']) }}
                    </div>

                </div>
                <hr>

                <div class="row mt-4">

                    <div class="col-md-6 col-12 form-group">
                        {{ Form::label('address', 'Client Address', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::textarea('client_address', isset($list->client_address) ? $list->client_address : '', ['class' => 'form-control ', 'placeholder' => 'Client Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off']) }}
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
                        <input type="hidden" value="{{ $list->title_image}}" name="old_title_image">

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
                                    <div class="col-xs-2 col-sm-3 col-md-6 col-lg-2  mt-5" data-kt-image-input="true" id='{{ $row->id }}'>
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
        $(function() {
            $("#category").change(function() {

                $(".div_carpet_area").attr("style", "display:none"); 
                $(".div_build_up_area").attr("style", "display:none"); 
                $(".div_plot_area").attr("style", "display:none"); 
                $(".div_hecta_area").attr("style", "display:none"); 
                $(".div_acre").attr("style", "display:none"); 
                $(".div_house_type").attr("style", "display:none"); 
                $(".div_furnished").attr("style", "display:none"); 

                $(".div_house_no").attr("style", "display:none");
                $(".div_survey_no").attr("style", "display:none");
                $(".div_plot_no").attr("style", "display:none");


                $("#carpet_area").val('');
                $("#build_up_area").val('');
                $("#plot_area").val('');
                $("#hecta_area").val('');
                $("#acre").val('');
                $("#house_type").val('').trigger('change');
                $("#furnished").val('').trigger('change');
                
                $("#house_no").val('');
                $("#survey_no").val('');
                $("#plot_no").val('');
              
                var parameter_types = $(this).find(':selected').data('parametertypes');
                

                if(parameter_types != undefined){
                    var strArray = parameter_types.split(",");

                for(var i = 0; i < strArray.length; i++){
                    if(parseInt(strArray[i]) == 1){
                        $(".div_carpet_area").attr("style", "display:block"); 
                    }

                    if(parseInt(strArray[i]) == 2){
                        $(".div_build_up_area").attr("style", "display:block");   
                    }

                    if(parseInt(strArray[i]) == 3){
                        $(".div_plot_area").attr("style", "display:block");
                    }

                    if(parseInt(strArray[i]) == 4){
                        $(".div_hecta_area").attr("style", "display:block");
                    }
                    if(parseInt(strArray[i]) == 5){
                        $(".div_acre").attr("style", "display:block");
                    }
                    if(parseInt(strArray[i]) == 6){
                        $(".div_house_type").attr("style", "display:block");
                    }

                    if(parseInt(strArray[i]) == 7){
                        $(".div_furnished").attr("style", "display:block");
                    }

                    if(parseInt(strArray[i]) == 8){
                        $(".div_house_no").attr("style", "display:block");
                    }

                    if(parseInt(strArray[i]) == 9){
                        $(".div_survey_no").attr("style", "display:block");
                    }

                    if(parseInt(strArray[i]) == 10){
                        $(".div_plot_no").attr("style", "display:block");
                    }
                    
                }
                }

                
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
                        if(response.error == false) {
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
                            $("#"+id).html('');
                        }else if(response.error == true){
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
