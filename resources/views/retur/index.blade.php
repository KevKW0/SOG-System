@extends('layouts.master')

@section('title')
    Return
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Return</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border mb-3">
                        <div class="btn-group">
                            <button class="btn btn-success xs" onclick="addForm()"><i class="fa fa-plus-circle">
                                    New Transaction</i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered retur-table">
                            <thead>
                                <th>No</th>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Total Item</th>
                                <th>Discount</th>
                                <th>Grandtotal</th>
                                <th>Operator</th>
                                <th><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('retur.customer')
    @includeIf('retur.detail')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(function() {
            table = $('.retur-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('retur.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'customer_name'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'pay'
                    },
                    {
                        data: 'operator'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
            $('.customer-table').DataTable()
            table2 = $('.table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'rt',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'product_code'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'qty'
                    },
                    {
                        data: 'selling_price'
                    },
                    {
                        data: 'subtotal'
                    }
                ]
            })
        });

        function addForm() {
            $('#modal-customer').modal('show');
        }

        function showDetail(url) {
            $('#modal-detail').modal('show');

            table2.ajax.url(url)
            table2.ajax.reload()
        }

        function deleteData(url) {
            if (confirm('Are you sure want to delete this data?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Unable to Delete Data');
                        return;
                    });
            }
        }
    </script>
@endpush
