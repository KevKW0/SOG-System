<div class="modal" id="modal-customer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered customer-table">
                    <thead>
                        <th>No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($customers as $key => $customer)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><span class="badge badge-success">{{ $customer->customer_code }}</span></td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>
                                    <a href="{{ route('sales.create', $customer->id) }}" class="btn btn-primary btn-xs">
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
