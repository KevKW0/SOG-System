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
                        <label for="category_name" class="col-md-2 col-md-offset-1 control-label">Category</label>
                        <div class="col-md-8">
                            <input type="text" name="category_name" id="category_name"
                                class="form-control @error('category_name') is-invalid @enderror" required autofocus>
                            @error('category_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
