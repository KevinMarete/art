<div id="page-wrapper">
    <div class="row">
        <div class="col-md-5">
            <h3>USER INFO</h3>
        </div>
        <div class="col-md-7">
            <ol class="breadcrumb">
                <li><a href="<?php echo base_url('Admin/home'); ?>">Dashboard</a></li>
                <li class="active breadcrumb-item"><i class="fa fa-angle-double-right mx-2 white-text" aria-hidden="true"></i> User</li>
            </ol>
        </div>
    </div>
    <button class="btn btn-primary" onclick="add_user()"><i class="glyphicon glyphicon-plus"></i> Add User</button>
    <button class="btn btn-success" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
    <br/>
    <br/>
    <table id="table" class="table table-responsive table-striped table-bordered table-condensed" width="100%">
        <thead>
            <tr>
                <th class="col-lg-4 col-md-4">User Name</th>
                <th class="col-lg-6 col-md-4">Phone Number</th>
                <th class="col-lg-2 col-md-4">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>

        <tfoot>
            <tr>
                <th class="col-lg-4 col-md-4">User Name</th>
                <th class="col-lg-6 col-md-4">Phone Number</th>
                <th class="col-lg-2 col-md-4">Action</th>
            </tr>
        </tfoot>
    </table>
</div>

<script src="<?php echo base_url() . 'public/admin/js/settings_user.js'; ?>"></script>

<!--Add or Edit User Modal-->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 pull-left">User Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Phone Number</label>
                            <div class="col-md-9">
                                <input name="phone" placeholder="254700000000" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->