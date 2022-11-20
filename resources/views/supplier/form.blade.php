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
                        <label for="name" class="col-md-2 col-md-offset-1 control-label">Name</label>
                        <div class="col-md-8">
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-md-2 col-md-offset-1 control-label">Phone</label>
                        <div class="col-md-8">
                            <input type="number" name="phone" id="phone"
                                class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-md-2 col-md-offset-1 control-label">Address</label>
                        <div class="col-md-8">
                            <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
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
