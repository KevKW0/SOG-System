<div class="modal" id="modal-product" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered product-table">
                    <thead>
                        <th>No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Purchase Price</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><span class="badge badge-success">{{ $product->product_code }}</span></td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->purchase_price }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs"
                                        onclick="chooseProducts('{{ $product->id }}', '{{ $product->product_code }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Choose
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
