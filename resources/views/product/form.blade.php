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
                        <label for="product_name" class="col-md-2 col-md-offset-1 control-label">Product Name</label>
                        <div class="col-md-8">
                            <input type="text" name="product_name" id="product_name"
                                class="form-control @error('product_name') is-invalid @enderror" required autofocus>
                            @error('product_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_category" class="col-md-2 col-md-offset-1 control-label">Category</label>
                        <div class="col-md-8">
                            <select name="id_category" id="id_category" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="merk" class="col-md-2 col-md-offset-1 control-label">Merk</label>
                        <div class="col-md-8">
                            <input type="text" name="merk" id="merk"
                                class="form-control @error('merk') is-invalid @enderror" required>
                            @error('merk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="purchase_price" class="col-md-2 col-md-offset-1 control-label">Purchase
                            Price</label>
                        <div class="col-md-8">
                            <input type="number" name="purchase_price" id="purchase_price" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="selling_price" class="col-md-2 col-md-offset-1 control-label">Selling Price</label>
                        <div class="col-md-8">
                            <input type="number" name="selling_price" id="selling_price" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="stock" class="col-md-2 col-md-offset-1 control-label">Stock</label>
                        <div class="col-md-8">
                            <input type="text" name="stock" id="stock" class="form-control" disabled
                                value="0">
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
