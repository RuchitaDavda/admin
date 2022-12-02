@extends('layouts.main')

@section('title')
    Categories
    <link rel="stylesheet" href="https://bevacqua.github.io/dragula/dist/dragula.css" />
@endsection

@section('page-title')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" /> --}}

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

            <div class="card-header">

                <div class="divider">
                    <div class="divider-text">
                        <h4>Create Category</h4>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            {!! Form::open(['url' => route('categories.store'), 'files' => true]) !!}
                            {{-- <div class="row mt-1"> --}}

                            <div class="form-group row">

                                {{ Form::label('category', 'Category', ['class' => 'col-sm-1 col-form-label text-center']) }}
                                <div class="col-sm-2">
                                    {{ Form::text('category', '', ['class' => 'form-control', 'placeholder' => 'Category', 'required' => true]) }}

                                </div>

                                {{ Form::label('type', 'Parameter Type', ['class' => 'col-sm-2 col-form-label text-center']) }}
                                <div class="col-sm-3 ">
                                    <select name="parameter_type[]" class="select2 form-select form-control-sm"
                                        id="select_parameter_type" multiple>
                                        @foreach ($parameters as $parameter)
                                            <option value={{ $parameter->id }}>{{ $parameter->name }}</option>
                                        @endforeach



                                    </select>



                                </div>

                                {{ Form::label('image', 'Image', ['class' => 'col-sm-1 col-form-label text-center']) }}
                                <div class="col-sm-2">
                                    {{ Form::file('image', ['class' => 'form-control']) }}
                                    @if (count($errors) > 0)
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger error-msg">{{ $error }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="card-footer"> --}}
                            <div class="col-sm-12 d-flex justify-content-end">
                                {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                            </div>
                            {{-- </div> --}}
                            {{-- </div> --}}
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('categoriesList') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                            data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true">ID</th>
                                    <th scope="col" data-field="image" data-sortable="false">Image</th>
                                    <th scope="col" data-field="category" data-sortable="true">Category</th>
                                    <th scope="col" data-field="status" data-sortable="true">Status</th>
                                    <th scope="col" data-field="type" data-sortable="false">Type</th>
                                    <th scope="col" data-field="sequence" data-sortable="true">Sequence</th>
                                    <th scope="col" data-field="operate" data-sortable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- EDIT MODEL MODEL -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Edit Categories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ url('categories-update') }}" class="form-horizontal" enctype="multipart/form-data"
                        method="POST" data-parsley-validate>

                        @csrf
                        <input type="hidden" id="old_image" name="old_image">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="col-md-12 col-12">
                                    <div class="form-group mandatory">
                                        <label for="edit_category" class="form-label col-12 text-center">Category</label>
                                        <input type="text" id="edit_category" class="form-control col-12"
                                            placeholder="Name" name="edit_category" data-parsley-required="true">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12">
                                {{ Form::label('type', 'Type', ['class' => 'col-sm-12 col-form-label text-center']) }}
                                {{-- <div class="col-sm-12 sequence">



                                    <select name="edit_parameter_type[]" id="edit_parameter_type" class="chosen-select"
                                        multiple>
                                        @foreach ($parameters as $parameter)
                                            <option value={{ $parameter->id }}>{{ $parameter->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div> --}}


                                <div id="output"></div>

                                <select data-placeholder="Choose parameter ..." name="edit_parameter_type[]"
                                    id="edit_parameter_type" multiple class="chosen-select">
                                    @foreach ($parameters as $parameter)
                                        <option value={{ $parameter->id }}>{{ $parameter->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <input type="submit"> --}}

                            </div>

                            <div class="col-sm- sequence">


                                <div id="par" class="row pt-3">

                                </div>
                                <input type="hidden" name="update_seq" id="update_seq">
                                <button id="btn_seq" value="change"
                                    class="btn btn-primary waves-effect waves-light">Change</button>
                                {{-- </div> --}}
                            </div>
                            {{ Form::label('image', 'Image', ['class' => 'col-sm-12 col-form-label text-center']) }}
                            <div class="col-sm-12">
                                {{ Form::file('image', ['class' => 'form-control']) }}
                                @if (count($errors) > 0)
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger error-msg">{{ $error }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-sm-12 col-12">
                                <div class="form-group mandatory">
                                    <label for="email" class="form-label col-12 text-center">Status</label>
                                    {!! Form::select('status', ['0' => 'Inactive', '1' => 'Active'], '', [
                                        'class' => 'form-select',
                                        'id' => 'status',
                                    ]) !!}

                                </div>
                            </div>

                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- EDIT MODEL -->
@endsection

@section('script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/$VERSION/dragula.min.js'></script>
    <script>
        $('#add').hide();

        $('#add_parameter').click(function() {
            // console.log($('parameter').val());
            $('#add').slideToggle();



        });
        $('#add_parameter_value').click(function() {
            console.log("in");
            param = $('#parameter').val();
            console.log($('#Parameter').val());

            $('#select_parameter_type').append($('<option></option>').val('adddd').html($('#Parameter').val()));

        });

        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }

        function disable(id) {
            $.ajax({
                url: "{{ route('customer.categoriesstatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": 0,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Category Deactive successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }

                },
                error: function(error) {

                }
            });
        }

        function active(id) {
            $.ajax({
                url: "{{ route('customer.categoriesstatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": 1,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Category Active successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }
                },
                error: function(error) {

                }
            });
        }

        $('#btn_seq').click(function(e) {
            e.preventDefault();

            console.log("click...");

            var sequence = [];
            $('.seq').each(function() {
                console.log($(this).attr('id'));
                sequence.push($(this).attr('id'));

            });
            console.log(sequence.toString());
            $('#update_seq').val(sequence.toString());

        });

        $('#edit_parameter_type').on('change', function() {

            $('#edit_parameter_type option:not(:selected)').each(function() {
                console.log('not::' + ($('.' + this.text)).html());
                ($('.' + this.text)).remove();
                var sequence = [];
                $('.seq').each(function() {

                    console.log("in");
                    sequence.push($(this).attr('id'));

                });
                $('#update_seq').val(sequence.toString());
            });

            $("#edit_parameter_type option:selected").map(function() {
                    console.log(this.text + ':' + ($('#par').find($('.' + this.text)).length));


                    if ($('#par').find($('.' + this.text)).length == 0) {

                        console.log(this.text + ':' + $('#' + this.value).length);


                        $('#par').append($(


                            '<div id = "' + this.value + '" class="col-sm-6 left1 seq ' + this.text +
                            '">' +
                            this.text +
                            '</div>'
                            // '<div id = "left1" class="col-sm-6 ' + this.text + '"> <p id = "' + this
                            // .value +
                            // '" name="' + this.text +
                            // '" class="seq" value="' +
                            // this.value +
                            // '"> ' + this.text +
                            // ' </p></div>'

                        ));
                    }
                }

            );


            var sequence = [];
            $('.seq').each(function() {

                console.log("in");
                sequence.push($(this).attr('id'));

            });


            $('#update_seq').val(sequence.toString());



        });

        function setValue(id) {


            $("#edit_id").val(id);
            $("#edit_category").val($("#" + id).parents('tr:first').find('td:nth-child(3)').text());
            $("#sequence").val($("#" + id).parents('tr:first').find('td:nth-child(6)').text());

            $("#old_image").val($("#" + id).data('oldimage'));
            $("#status").val($("#" + id).data('status')).trigger('change');

            var type = ($("#" + id).data('types')).toString();

            if (type != '') {
                $('#edit_parameter_type').val(type.split(',')).trigger('change');
            }

            $('#par').empty();
            str = '';
            $("#edit_parameter_type :selected").each(function() {

                str += this.value + ',';



                $('#par').append($(
                    '<div id = "' + this.value + '" class="col-sm-6 left1 seq ' + this.text + '">' + this
                    .text +
                    '</div>'

                ));

            });
            console.log(str);
            $(".chosen-select").val(str.split(',')).trigger('chosen:updated');

            var sequence = [];
            $('.seq').each(function() {

                console.log("in");
                sequence.push($(this).attr('id'));

            });
            $('#update_seq').val(sequence.toString());

            dragula(
                [document.querySelector('#par')].concat(
                    Array.from(document.querySelectorAll('.left1'))
                ), {
                    // revertOnSpill: true

                });
            // dragula([$('#par'), $('#left1')]);

        }
        var sequence = [];
        $('.seq').each(function() {


            sequence.push($(this).attr('id'));

        });

        $('#update_seq').val(sequence.toString());
        document.getElementById('output').innerHTML = location.search;
        $(".chosen-select").chosen();
    </script>
@endsection
