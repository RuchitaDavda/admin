@extends('layouts.main')

@section('title')
    Packages
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

            <div class="card">
                {!! Form::open(['route' => 'package.store', 'data-parsley-validate', 'files' => true]) !!}
                <div class="card-body">

                    <div class="row ">

                        <div class="col-md-4 col-12 form-group mandatory">

                            {{ Form::label('name', 'name', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::text('name', '', ['class' => 'form-control ', 'placeholder' => 'Package Name', 'data-parsley-required' => 'true', 'id' => 'name']) }}

                        </div>
                        <div class="col-md-4 col-12 form-group mandatory">

                            {{ Form::label('duration', 'duration', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::text('duration', '', ['class' => 'form-control ', 'placeholder' => 'Package duration', 'data-parsley-required' => 'true', 'id' => 'duration']) }}

                        </div>
                        <div class="col-md-4 col-12 form-group mandatory">

                            {{ Form::label('price', 'price', ['class' => 'form-label col-12 text-center']) }}
                            {{ Form::text('price', '', ['class' => 'form-control ', 'placeholder' => 'Package price', 'data-parsley-required' => 'true', 'id' => 'price']) }}

                        </div>

                        <div class="col-12 col-xs-12 d-flex justify-content-end">


                            {{ Form::submit('Add Package', ['class' => 'btn btn-primary']) }}

                        </div>

                    </div>



                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @if (has_permissions('create', 'property'))
            <div class="card-header">

                <div class="row ">

                </div>
        @endif

        <hr>
        <div class="card-body">

            {{-- <div class="row " id="toolbar"> --}}

            <div class="row">
                <div class="col-12">

                    <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                        data-toggle="table" data-url="{{ url('package_list') }}" data-click-to-select="true"
                        data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200,All]"
                        data-search="true" data-search-align="right" data-toolbar="#toolbar" data-show-columns="true"
                        data-show-refresh="true" data-fixed-columns="true" data-fixed-number="1" data-fixed-right-number="1"
                        data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                        data-pagination-successively-size="3" data-query-params="queryParams">
                        <thead>
                            <tr>
                                <th scope="col" data-field="id" data-halign="center" data-sortable="true">ID</th>
                                <th scope="col" data-field="name" data-halign="center" data-sortable="true">Name
                                </th>

                                <th scope="col" data-field="duration" data-halign="center" data-sortable="false">
                                    Duration</th>
                                <th scope="col" data-field="price" data-halign="center" data-sortable="false">
                                    Price
                                </th>
                                <th scope="col" data-field="status" data-halign="center" data-sortable="false">
                                    Status
                                </th>
                                @if (has_permissions('update', 'property_inquiry'))
                                    <th scope="col" data-field="operate" data-halign="center" data-sortable="false">
                                        Action</th>
                                @endif

                            </tr>
                        </thead>
                    </table>
                </div>
            </div>


        </div>
        </div>


        <!-- EDIT MODEL MODEL -->
        <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">Edit Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ url('package-update') }}" class="form-horizontal" enctype="multipart/form-data"
                            method="POST" data-parsley-validate>

                            @csrf

                            <input type="hidden" id="edit_id" name="edit_id">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="col-md-12 col-12">
                                        <div class="form-group mandatory">
                                            <label for="edit_name" class="form-label col-12 text-center">Name</label>
                                            <input type="text" id="edit_name" class="form-control col-12"
                                                placeholder="Name" name="edit_name" data-parsley-required="true">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-12">

                                    <div class="col-md-12 col-12">
                                        <div class="form-group mandatory">
                                            <label for="edit_duration"
                                                class="form-label col-12 text-center">Duration</label>
                                            <input type="text" id="edit_duration" class="form-control col-12"
                                                placeholder="Name" name="edit_duration" data-parsley-required="true">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-12">

                                    <div class="col-md-12 col-12">
                                        <div class="form-group mandatory">
                                            <label for="edit_price" class="form-label col-12 text-center">Price</label>
                                            <input type="text" id="edit_price" class="form-control col-12"
                                                placeholder="Name" name="edit_price" data-parsley-required="true">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12 col-12">
                                    <div class="form-group mandatory">
                                        <label for="email" class="form-label col-12 text-center">Status</label>
                                        {!! Form::select('status', ['0' => 'OFF', '1' => 'ON'], '', [
                                            'class' => 'form-select',
                                            'id' => 'status',
                                        ]) !!}

                                    </div>
                                </div>

                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect"
                            data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- EDIT MODEL -->
    </section>
@endsection

@section('script')
    <script>
        function queryParams(p) {

            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,

            };
        }

        function setValue(id) {

            $("#edit_id").val(id);
            $("#edit_name").val($("#" + id).parents('tr:first').find('td:nth-child(2)').text());
            $("#edit_duration").val($("#" + id).parents('tr:first').find('td:nth-child(3)').text());
            $("#edit_price").val($("#" + id).parents('tr:first').find('td:nth-child(4)').text());





        }
    </script>

    <script>
        function disable(id) {
            $.ajax({
                url: "{{ route('package.updatestatus') }}",
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
                            text: 'Package OFF successfully',
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
                url: "{{ route('package.updatestatus') }}",
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
                            text: 'Package ON successfully',
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
    </script>
@endsection
