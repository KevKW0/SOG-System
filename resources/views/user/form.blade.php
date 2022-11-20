<div class="modal" id="modal-form" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" class="form-horizontal">
            @csrf

            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 col-md-offset-1 control-label">Name</label>
                        <div class="col-md-8">
                            <input type="text" name="name" id="name" class="form-control" required autofocus>
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-md-offset-1 control-label">Email</label>
                        <div class="col-md-8">
                            <input type="email" name="email" id="email" class="form-control" required autofocus>
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-md-3 col-md-offset-1 control-label">Role</label>
                        <div class="col-md-8">
                            <select name="role" id="role" class="form-control">
                                <option value="Supervisor">Supervisor</option>
                                <option value="Operator">Operator</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-3 col-md-offset-1 control-label">Password</label>
                        <div class="col-md-8">
                            <input type="password" name="password" id="password" class="form-control" required
                                minlength="8">
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-3 col-md-offset-1 control-label">Confirm
                            Password</label>
                        <div class="col-md-8">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required data-match="#password">
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal"><i
                            class="fa fa-arrow-circle-left"></i> Cancel</button>
                </div>
            </div>

        </form>
    </div>
</div>
