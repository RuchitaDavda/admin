@extends('layouts.main')

@section('title')
    Property Inquiry
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

            <div class="card-body">
                <div class="row justify-content-center" id="toolbar">


                    <div class="col-sm-12">

                        {{-- {{ Form::label('filter_status', 'Status', ['class' => 'form-label col-12 text-center']) }} --}}
                        <select id="filter_status" class="form-select form-control-sm">
                            <option value="">Select Status </option>
                            <option value="0">Pending</option>
                            <option value="1">Accept</option>
                            <option value="2">Complete</option>
                            <option value="3">Cancle</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('getPropertyInquiryList') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                            data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-halign="center" data-sortable="true">ID</th>
                                    <th scope="col" data-field="title" data-halign="center" data-sortable="false">Title
                                    </th>
                                    <th scope="col" data-field="property_owner" data-halign="center"
                                        data-sortable="false">Owner Name</th>
                                    <th scope="col" data-field="property_mobile" data-halign="center"
                                        data-sortable="false">Owner Mobile</th>
                                    <th scope="col" data-field="name" data-halign="center" data-sortable="false">Inquiry
                                        By</th>
                                    <th scope="col" data-field="address" data-halign="center" data-sortable="false">
                                        Customer Address</th>

                                    <th scope="col" data-field="client_address" data-halign="center"
                                        data-sortable="false">Client Address</th>

                                    <th scope="col" data-field="location" data-halign="center" data-sortable="false">
                                        Location</th>
                                    <th scope="col" data-field="email" data-halign="center" data-sortable="false">Email
                                    </th>
                                    <th scope="col" data-field="mobile" data-halign="center" data-sortable="false">Mobile
                                    </th>
                                    <th scope="col" data-field="carpet_area" data-halign="center"data-visible="false"
                                        data-sortable="false">Carpet Area</th>
                                    <th scope="col" data-field="built_up_area" data-halign="center" data-visible="false"
                                        data-sortable="false">Built Up Area</th>


                                    <th scope="col" data-field="plot_area" data-halign="center" data-visible="false"
                                        data-sortable="false">Plot Area</th>
                                    <th scope="col" data-field="hecta_area" data-halign="center" data-visible="false"
                                        data-sortable="false">Hecta Area</th>

                                    <th scope="col" data-field="acre" data-halign="center" data-visible="false"
                                        data-sortable="false">Acre</th>


                                    <th scope="col" data-field="inquiry_created" data-halign="center"
                                        data-sortable="false">Inquiry Posted
                                    </th>
                                    <th scope="col" data-field="status" data-halign="center" data-sortable="false">
                                        Status
                                    </th>

                                    @if (has_permissions('update', 'property') || has_permissions('delete', 'property'))
                                        <th scope="col" data-field="operate" data-events="actionEvents"
                                            data-sortable="false">Action</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </section>


    <!-- EDIT MODEL MODEL -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Edit Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="status" class="form-label col-12 text-center">Status</label>
                                    <select name="status" id="status" class="select2 form-select form-control-sm"
                                        id="status" required='true'>
                                        <option value="0">Pending</option>
                                        <option value="1">Accept</option>
                                        <option value="2">Complete</option>
                                        <option value="3">Cancle</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>

                    <a onclick="return changeStatus();"class="btn btn-primary waves-effect waves-light">Save</a>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- EDIT MODEL -->



    <!-- VIEW PROPERTY MODEL -->
    <div id="ViewPropertyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">View Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row ">
                        <div class="col-md-6 col-12 ">
                            <div class="col-md-12 col-12 form-group">
                                 <label for="title" class="form-label col-12 text-center">Title</label>
                                <input class="form-control " placeholder="Title" id="title" readonly type="text">
                            </div>
                        </div>

                        <div class="col-md-6 col-12 ">
                            <div class="row ">
                                <div class="col-md-3 col-12 form-group">
                                    <label for="category" class="form-label col-12 text-center">Category</label>
                                    <input class="form-control " placeholder="Category" id="category" readonly type="text">
                                </div>
                                <div class="col-md-3 col-12 form-group">
                                    <label for="district" class="form-label col-12 text-center">District</label>
                                    <input class="form-control " placeholder="District" readonly="true" id="district"
                                         type="text" >
                                   
                                </div>

                                <div class="col-md-3 col-12 form-group">
                                    <label for="taluka" class="form-label col-12 text-center">Taluka</label>
                                    <input class="form-control " placeholder="Taluka" id="taluka" readonly type="text">
                                </div>

                                <div class="col-md-3 col-12 form-group">
                                    <label for="village" class="form-label col-12 text-center">Village Or City</label>
                                    <input class="form-control " placeholder="Village Or City" id="village" readonly type="text">
                                </div>
                            </div>
                        </div>

                        
                    </div>


                    <div class="row ">
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12 form-group">
                                    <label for="property_type" class="form-label col-12 text-center">Property Type</label>
                                    <input class="form-control " placeholder="Property Type" id="property_type" readonly type="text">
                                </div>
                                <div class="col-md-6 col-12 form-group">
                                    <label for="price" class="form-label col-12 text-center">Price</label>
                                <input class="form-control " placeholder="Price" id="price" readonly type="text">
                            </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-4 col-12 form-group">
                                    <label for="unit_type" class="form-label col-12 text-center">Unit Type</label>
                                    <input class="form-control " placeholder="Property Type" id="unit_type" readonly type="text">
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <label for="latitude" class="form-label col-12 text-center">Latitude</label>
                                    <input class="form-control " placeholder="Latitude" id="latitude" readonly type="text">
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <label for="longitude" class="form-label col-12 text-center">Longitude</label>
                                    <input class="form-control " placeholder="Longitude" id="longitude" readonly type="text">
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="divider">
                        <div class="divider-text">Unit Value</div>
                    </div>


                    <div class="row  mt-4">
                        <div class="col-md-3 col-12 mt-2 mb-2 div_carpet_area" style="display: none;">
                            <label for="carpet_area" class="form-label col-12 text-center">Carpet Area</label>
                            <input class="form-control " placeholder="Carpet Area" id="carpet_area" readonly type="text">
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_build_up_area"  style="display: none;">
                            <label for="build_up_area" class="form-label col-12 text-center">Built-Up Area</label>
                            <input class="form-control " placeholder="Built-Up Area" id="build_up_area" readonly type="text">
                        </div>
                        <div class="col-md-3 col-12 mt-2 mb-2 div_plot_area"  style="display: none;">
                          
                            <label for="plot_area" class="form-label col-12 text-center">Plot Area</label>
                            <input class="form-control " placeholder="Built-Up Area" id="plot_area" readonly type="text">
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_hecta_area"  style="display: none;">
                            <label for="hecta_area" class="form-label col-12 text-center">Hecta Area</label>
                            <input class="form-control " placeholder="Hecta Area" id="hecta_area" readonly type="text">
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_acre"  style="display: none;">
                            <label for="acre" class="form-label col-12 text-center">Acre</label>
                            <input class="form-control " placeholder="Acre" id="acre" readonly type="text">
                        </div>
    
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_house_type"  style="display: none;">
                            <label for="house_type" class="form-label col-12 text-center">House Type</label>
                            <input class="form-control " placeholder="House Type" id="house_type" readonly type="text">
    
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_furnished"  style="display: none;">
                           
                            <label for="furnished" class="form-label col-12 text-center">Furnished</label>
                            <input class="form-control " placeholder="Furnished" id="furnished" readonly type="text">
    
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_house_no"  style="display: none;">
                           
                            <label for="house_no" class="form-label col-12 text-center">House No</label>
                            <input class="form-control " placeholder="House No" id="house_no" readonly type="text">
    
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_survey_no"  style="display: none;">
                            <label for="survey_no" class="form-label col-12 text-center">Survey No</label>
                            <input class="form-control " placeholder="Survey No" id="survey_no" readonly type="text">
    
                           
                        </div>
    
                        <div class="col-md-3 col-12 mt-2 mb-2 div_plot_no"  style="display: none;">
                            <label for="plot_no" class="form-label col-12 text-center">Plot No</label>
                            <input class="form-control " placeholder="Plot No" id="plot_no" readonly type="text">
    
                        </div>
    
                    </div>


                    <div class="row ">
                        <div class="col-md-12 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12 form-group">
                                    <label for="client_address" class="form-label col-12 text-center">Client Address</label>
                                    <textarea class="form-control " placeholder="Client Address" rows="2" id="client_address" autocomplete="off"  cols="50" readonly></textarea>
                                </div>
                                <div class="col-md-6 col-12 form-group">
                                    <label for="address" class="form-label col-12 text-center">Address</label>
                                    <textarea class="form-control " placeholder="Address" rows="2" id="address" autocomplete="off"  cols="50" readonly></textarea>
                            </div>
                            </div>
                        </div>

                    </div>

                    <div class="row ">
                        <div class="col-md-12 col-12">
                            <label for="description" class="form-label col-12 text-center">Description</label>
                            <p id="description"></p>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- VIEW PROPERTY MODEL -->
@endsection

@section('script')
    <script>
        var clipboard = new ClipboardJS('.CopyLocation');

        clipboard.on('success', function(e) {
            Toastify({
                text: 'Copied',
                duration: 1000,
                close: !0,
                backgroundColor: "#000000"
            }).showToast()
            e.clearSelection();
        });
    </script>
    <script>
        $('#filter_status').on('change', function() {
            $('#table_list').bootstrapTable('refresh');

        });

        $(document).ready(function() {
            var params = new window.URLSearchParams(window.location.search);

            if (params.get('status') != 'null') {
                $('#status').val(params.get('status')).trigger('change');
            }
        });

        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,
                status: $('#filter_status').val(),
            };
        }

        function setValue(id) {
            //$('#editUserForm').attr('action', ($('#editUserForm').attr('action') +'/edit/'+id));
            $("#edit_id").val(id);
            $("#status").val($("#" + id).data('status')).trigger('change');

        }

        function changeStatus() {
            var id = $("#edit_id").val();
            var status = $("#status").val();

            $.ajax({
                url: "{{ route('property-inquiry.updateStatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": status,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Inquiry Status Change successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                        $("#edit_id").val('');
                        $("#status").val(0).trigger('change');
                        $('#editModal').modal('toggle');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }

                }

            });
        }
    </script>

    <script>
        window.actionEvents = {
            'click .view-property': function(e, value, row, index) {
                $("#title").val(row.title);
                $("#category").val(row.category);
                $("#state").val(row.state);
                $("#district").val(row.district);
                $("#taluka").val(row.taluka);
                $("#village").val(row.village);
                $("#property_type").val(row.property_type);
                $("#price").val(row.price);
                $("#unit_type").val(row.unitType);
                $("#latitude").val(row.latitude);
                $("#longitude").val(row.longitude);
                $("#client_address").val(row.client_address);
                $("#address").val(row.address);
                $("#description").html(row.description);


                var strArray = row.category_parameter_types.split(",");
                for(var i = 0; i < strArray.length; i++){
                    if(parseInt(strArray[i]) == 1){
                        $(".div_carpet_area").attr("style", "display:block");
                        $("#carpet_area").val(row.carpet_area);
                    }

                    if(parseInt(strArray[i]) == 2){
                        $(".div_build_up_area").attr("style", "display:block");
                        $("#build_up_area").val(row.built_up_area);
                    }

                    if(parseInt(strArray[i]) == 3){
                        $(".div_plot_area").attr("style", "display:block");
                        $("#plot_area").val(row.plot_area);
                    }

                    if(parseInt(strArray[i]) == 4){
                        $(".div_hecta_area").attr("style", "display:block");
                        $("#hecta_area").val(row.hecta_area);
                    }
                    if(parseInt(strArray[i]) == 5){
                        $(".div_acre").attr("style", "display:block");
                        $("#acre").val(row.acre);
                    }
                    if(parseInt(strArray[i]) == 6){
                        $(".div_house_type").attr("style", "display:block");
                        $("#house_type").val(row.house_type);
                    }

                    if(parseInt(strArray[i]) == 7){
                        $(".div_furnished").attr("style", "display:block");
                        $("#furnished").val(row.furnished);
                    }

                    if(parseInt(strArray[i]) == 8){
                        $(".div_house_no").attr("style", "display:block");
                        $("#house_no").val(row.house_no);
                    }

                    if(parseInt(strArray[i]) == 9){
                        $(".div_survey_no").attr("style", "display:block");
                        $("#survey_no").val(row.survey_no);
                    }

                    if(parseInt(strArray[i]) == 10){
                        $(".div_plot_no").attr("style", "display:block");
                        $("#plot_no").val(row.plot_no);
                    }

                }
            }
        };
    </script>
@endsection
