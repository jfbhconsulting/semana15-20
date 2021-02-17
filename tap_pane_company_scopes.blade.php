<div class="row">
    <div class="col-lg">
        {{-- <h4 class="header-title mt-5 mt-lg-0">Phone number</h4> --}}
        {{-- <p class="text-muted font-14">
            A switch has the markup of a custom checkbox but uses the <code>.custom-switch</code> class to render a toggle switch. Switches also support the <code>disabled</code> attribute.
        </p> --}}

        {{-- <hr/> --}}

        <div class="tab-content">
            <div class="form-group">
                    <table id="tbl_phone_number" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>
                                    Service
                                </th>
                                <th class="text-center">
                                    Scope
                                </th>
                                <th class="text-center">
                                    Action &nbsp;&nbsp;
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_add_scope" title="Add new scope">
                                    <i class="dripicons-plus"></i>
                                </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="display: none;">
                                <td>
                                    <input type="text" name="landline" class="form-control" data-toggle="input-mask" data-mask-format="00-00-00-00-00" maxlength="9" placeholder="xx-xx-xx-xx-xx">
                                </td>
                                <td class="table-action text-center">
                                    <a href="javascript: void(0);" class="action-icon" onclick="deleteTr(this)">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </td>
                            </tr>
                            @if( sizeof($scopes) > 0 )
                            @foreach( $scopes as $scope)
                            <tr>
                                <td>
                                    {{-- <input type="text" name="landline" class="form-control" data-toggle="input-mask" data-mask-format="00-00-00-00-00" maxlength="9" value=" --}}{{ $scope->initials }}{{-- " placeholder="xx-xx-xx-xx-xx"> --}}
                                </td>
                                <td>
                                    {{-- <input type="text" name="extension" class="form-control" value=" --}}{{ $scope->scope }}{{-- " placeholder=""> --}}
                                </td>
                                <td class="table-action text-center">

                                    <a href="#" alt="Edit record" title="Edit record" class="action-icon" data-toggle="modal" data-target="#modal_update_reason_{{ $scope->id_company_scope }}">
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>

                                    <a href="#" class="action-icon" onclick="deleteRecord('company/scope',{{ $scope->id_company_scope }})">
                                        <i class="mdi mdi-delete"></i>
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <hr>
            </div>

        </div> <!-- end tab-content-->

    </div> <!-- end col -->

</div> <!-- end row -->


<!-- Modal add - scope -->
<div class="modal fade" id="modal_add_scope" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Add new scope</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">


                <form method="post" action="{{ URL::to('backend/companies/company/scopes/insert') }}/{{ $basics->id_company }}" novalidate autocomplete="off">
                    <input type="hidden" name="cve_company" value="{{ $basics->id_company }}">
                    <div id="div_content_address" class="tab-content">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="simpleinput">Type of Service</label>
                                @if( sizeof($types_service) > 0 )
                                <select class="form-control" name="type_service">
                                    @foreach( $types_service as $type_ser)
                                        <option <?php echo 'selected'?> value="{{$type_ser->id_type_service}}|{{$type_ser->key}}">{{$type_ser->key}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label for="simpleinput">Scope</label>
                                <textarea class="form-control" placeholder="Scope" name="scope"  id="exampleFormControlTextarea1" rows="10"></textarea>
                            </div>
                        {{-- </div>
                        <div class="form-row"> --}}
                        </div>
                    </div>

                    <hr>

                    <!-- row botones -->
                    <div class="form-group">
                        <button type="reset" data-dismiss="modal" class="btn btn-dark btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Save scope</button>
                    </div>
                    <!-- ./row botones -->
                </form>



            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Modal - scope -->


<!--Modal update-->
@if( sizeof($scopes) > 0 )
@foreach( $scopes as $scope)
<div class="modal fade" id="modal_update_reason_{{ $scope->id_company_scope }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Update social reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form method="post" action="{{ URL::to('backend/companies/company/scope/update') }}/{{ $basics->id_company }}" novalidate autocomplete="off">
                    <div id="div_content_address" class="tab-content">
                        <input type="hidden" value="{{$scope->id_company_scope}}" name="id_company_scope">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="simpleinput">Type service</label>
                                @if( sizeof($types_service) > 0 )
                                <select class="form-control" name="type_service">
                                    @foreach( $types_service as $type_ser)
                                        <option value="{{$type_ser->id_type_service}}|{{$type_ser->key}}">{{$type_ser->key}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <label for="simpleinput">Scope</label>
                                <textarea class="form-control" placeholder="Scope" name="scope"  id="exampleFormControlTextarea1" rows="10">{{$scope->scope}}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- row botones -->
                    <div class="form-group">
                        <button type="reset" data-dismiss="modal" class="btn btn-dark btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Save scope</button>
                    </div>
                    <!-- ./row botones -->
                </form>



            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endforeach
@endif