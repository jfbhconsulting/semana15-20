<div class="row">
    <div class="col-lg">
        {{-- <h4 class="header-title mt-5 mt-lg-0">Phone number</h4> --}}
        {{-- <p class="text-muted font-14">
            A switch has the markup of a custom checkbox but uses the <code>.custom-switch</code> class to render a toggle switch. Switches also support the <code>disabled</code> attribute.
        </p> --}}

        {{-- <hr/> --}}

        <div class="tab-content">
            <div class="form-group">
                <!-- <form id="frm_phon_number" method="post" action="{{ URL::to('backend/companies/phone_number/update') }}" novalidate autocomplete="off"> -->
                <form id="frm_phon_number" novalidate autocomplete="off">
                    <table id="tbl_phone_number" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>
                                    Social reason
                                </th>
                                <th class="text-center">RFC</th>
                                <th class="text-center">
                                    Action &nbsp;&nbsp;
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_add_social_reason" title="Add new social reason">
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
                            @if( sizeof($socialreason) > 0 )
                            @foreach( $socialreason as $reason)
                            <tr>
                                <td>
                                    {{-- <input type="text" name="landline" class="form-control" data-toggle="input-mask" data-mask-format="00-00-00-00-00" maxlength="9" value=" --}}{{ $reason->socialreason }}{{-- " placeholder="xx-xx-xx-xx-xx"> --}}
                                </td>
                                <td>
                                    {{-- <input type="text" name="extension" class="form-control" value=" --}}{{ $reason->RFC }}{{-- " placeholder=""> --}}
                                </td>
                                <td class="table-action text-center">

                                    <a href="#" alt="Edit record" title="Edit record" class="action-icon" data-toggle="modal" data-target="#modal_update_reason_{{ $reason->id_company_socialreason }}">
                                        <i class="mdi mdi-square-edit-outline"></i>
                                    </a>

                                    <a href="#" class="action-icon" onclick="deleteRecord('company/socialreason',{{ $reason->id_company_socialreason }})">
                                        <i class="mdi mdi-delete"></i>
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <hr>
                    {{-- <!-- row botones -->
                    <div class="form-group">
                        <button type="reset" class="btn btn-dark btn-sm">Cancel</button>
                        <button type="button" id="btn_phon_number" class="btn btn-success btn-sm">Save phone numbers</button>
                    </div> <!-- ./row botones --> --}}
                </form>
            </div>

        </div> <!-- end tab-content-->

    </div> <!-- end col -->

</div> <!-- end row -->




<!-- Modal add - social reason -->
<div class="modal fade" id="modal_add_social_reason" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Add new social reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">


                <form method="post" action="{{ URL::to('backend/companies/company/socialreason/insert') }}/{{ $basics->id_company }}" novalidate autocomplete="off">
                    <input type="hidden" name="cve_company" value="{{ $basics->id_company }}">
                    <div id="div_content_address" class="tab-content">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="simpleinput">Social reason</label>
                                <input type="text" name="social_reason" class="form-control" data-toggle="input-mask"  placeholder="Coca Cola">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="simpleinput">RFC</label>
                                <input type="text" name="rfc" maxlength="13" class="form-control" placeholder="RFC">
                            </div>
                        {{-- </div>
                        <div class="form-row"> --}}
                        </div>
                    </div>

                    <hr>

                    <!-- row botones -->
                    <div class="form-group">
                        <button type="reset" data-dismiss="modal" class="btn btn-dark btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Save Social reason</button>
                    </div>
                    <!-- ./row botones -->
                </form>



            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.Modal - social -->


<!--Modal update-->
@if( sizeof($socialreason) > 0 )
@foreach( $socialreason as $social)
<div class="modal fade" id="modal_update_reason_{{ $social->id_company_socialreason }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myCenterModalLabel">Update social reason</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <form method="post" action="{{ URL::to('backend/companies/company/socialreason/update') }}/{{ $basics->id_company }}" novalidate autocomplete="off">
                    <input type="hidden" name="cve_social_reason" value="{{ $social->id_company_socialreason }}">
                    <div id="div_content_address" class="tab-content">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="simpleinput">Social reason</label>
                                <input type="text" name="social_reason" class="form-control" value="{{ $social->socialreason }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="simpleinput">RFC</label>
                                <input type="text" maxlength="13" name="rfc" class="form-control" value="{{ $social->RFC }}" placeholder="RFC">
                            </div>
                        {{-- </div>
                        <div class="form-row"> --}}
                        </div>
                    </div>

                    <hr>

                    <!-- row botones -->
                    <div class="form-group">
                        <button type="reset" data-dismiss="modal" class="btn btn-dark btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Save Social reason</button>
                    </div>
                    <!-- ./row botones -->
                </form>



            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endforeach
@endif