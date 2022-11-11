@extends('layouts.main')

@section('title')
    Send Notification
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
    <div class="row">


        <section class="section">

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <form action="{{ route('notification.store') }}" class="needs-validation" method="post" novalidate
                            enctype="multipart/form-data">

                            @csrf
                            <div class="card-body">
                                <textarea id="user_id" name="user_id" style="display: none"></textarea>
                                <textarea id="fcm_id" name="fcm_id" style="display: none"></textarea>

                                <input type="hidden" name="type" value="0">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="form-label">Select User</label>
                                        <select id="send_type" name="send_type" class="form-control select2" required>
                                            <option value="1">All</option>
                                            <option value="0">Selected Only</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="form-label">Title</label>
                                        <input name="title" type="text" class="form-control" placeholder="Title"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label class="form-label">Message</label>
                                        <textarea name="message" class="form-control" placeholder="Message" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input id="include_image" name="include_image" type="checkbox"
                                                class="form-check-input">
                                            <label class="form-check-label">Include Image</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" id="show_image" style="display: none">
                                    <div class="col-md-12">
                                        <label class="form-label">Image</label>
                                        <input id="file" name="file" type="file" accept="image/*"
                                            class="form-control">
                                        <p style="display: none" id="img_error_msg" class="badge rounded-pill bg-danger">
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                                </div>
                            </div>

                            {{-- <div class="card-footer"> --}}

                            {{-- </div> --}}
                        </form>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">

                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    <table class="table-light" aria-describedby="mydesc" class='table-striped'
                                        id="users_list" data-toggle="table" data-url="{{ url('customerList') }}"
                                        data-click-to-select="true" data-side-pagination="server" data-pagination="true"
                                        data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true"
                                        data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                                        data-fixed-columns="true" data-fixed-number="1" data-fixed-right-number="1"
                                        data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                                        data-sort-order="desc" data-pagination-successively-size="3"
                                        data-query-params="queryParams_1">
                                        <thead>
                                            <tr>
                                                <th scope="col" data-field="state" data-checkbox="true"></th>
                                                <th scope="col" data-field="id" data-sortable="true">ID</th>
                                                <th scope="col" data-field="name" data-sortable="true">Name</th>
                                                <th scope="col" data-field="mobile" data-sortable="true">Number</th>

                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="card">

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div id="toolbar">
                                <button class="btn btn-danger btn-sm btn-icon text-white" id="delete_multiple"
                                    title="{{ trans('message.multiple_detele_data') }}"><em
                                        class='fa fa-trash'></em></button>
                            </div>
                            <table aria-describedby="mydesc" class='table-striped' id="table_list1" data-toggle="table"
                                data-url="{{ url('notificationList') }}" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-toolbar="#toolbar"
                                data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                                data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                                data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                data-pagination-successively-size="3">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="state" data-checkbox="true"></th>
                                        <th scope="col" data-field="id" data-sortable="true">Id</th>
                                        <th scope="col" data-field="title" data-sortable="true">Title</th>
                                        <th scope="col" data-field="message" data-sortable="true">Message</th>
                                        <th scope="col" data-field="image" data-sortable="false">Image</th>
                                        <th scope="col" data-field="type" data-sortable="true">Type</th>
                                        <th scope="col" data-field="send_type" data-sortable="true">Message Type</th>
                                        <th scope="col" data-field="operate" data-sortable="false"
                                            data-events="actionEvents">Action</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        table = $('#users_list');
        var fcm_list = [];
        var user_list = [];
        $(table).on('check.bs.table  uncheck.bs.table', function(e, row) {
            var fcm_id = row.fcm_id;
            var user_id = row.id;


            if (e.type == 'check') {
                fcm_list.push(fcm_id);
                user_list.push(user_id);
            } else {
                var fcm_index = fcm_list.indexOf(fcm_id);
                if (fcm_index > -1) {
                    fcm_list.splice(fcm_index, 1);
                }
                var user_index = user_list.indexOf(user_id);
                if (user_index > -1) {
                    user_list.splice(user_index, 1);
                }
            }
            $('textarea#fcm_id').val(fcm_list);
            $('textarea#user_id').val(user_list);
        });
    </script>
    <script>
        window.actionEvents = {};

        function queryParams_1(p) {
            return {
                "status": $('#filter_status').val(),
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }

        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }
    </script>

    <script>
        $("#include_image").change(function() {
            if (this.checked) {
                $('#show_image').show('fast');
                $('#file').attr('required', 'required');
            } else {
                $('#file').val('');
                $('#file').removeAttr('required');
                $('#show_image').hide('fast');
            }
        });
    </script>


    <script type="text/javascript">
        var _URL = window.URL || window.webkitURL;

        $("#file").change(function(e) {
            var file, img;

            if ((file = this.files[0])) {
                img = new Image();
                img.onerror = function() {
                    $('#file').val('');
                    $('#img_error_msg').html('{{ trans('message.invalid_image_type') }}');
                    $('#img_error_msg').show().delay(3000).fadeOut();
                };
                img.src = _URL.createObjectURL(file);
            }
        });
    </script>



<script type="text/javascript">
    $(document).on('click', '.delete-data', function () {
        if (confirm('Are you sure? Want to delete ?')) {
            var id = $(this).data("id");
            var image = $(this).data("image");
            $.ajax({
                url: "{{url('notification-delete')}}",
                type: "GET",
                data: {id: id, image: image},
                success: function (result) {
                    if (result.error) {
                        errorMsg(result.message);
                    } else {
                        $('#table_list1').bootstrapTable('refresh');
                        successMsg(result.message);
                    }
                }
            });
        }
    });
</script>


<script type="text/javascript">
    $('#delete_multiple').on('click', function (e) {
        table = $('#table_list1');
        delete_button = $('#delete_multiple');
        selected = table.bootstrapTable('getSelections');
        ids = "";
        $.each(selected, function (i, e) {
            ids += e.id + ",";
        });
        ids = ids.slice(0, -1);
        if (ids == "") {
            alert('please Select Some Data');
        } else {
            if (confirm('Are You Sure Delete Selected Data')) {
                $.ajax({
                    url: "{{url('notification-multiple-delete')}}",
                    type: "POST",
                    data: {"_token": "{{ csrf_token() }}", id:ids},
                    beforeSend: function () {
                        delete_button.html('<em class="fa fa-spinner fa-pulse"></em>');
                    },
                    success: function (result) {
                        if (result.error) {
                            errorMsg(result.message);
                        } else {
                            delete_button.html('<em class="fa fa-trash"></em>');
                            $('#table_list1').bootstrapTable('refresh');
                            successMsg(result.message);
                        }
                    }
                });
            }
        }
    });
</script>



@endsection
