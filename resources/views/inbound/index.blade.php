@extends('layouts.master')

@section('title')
    Inbound List
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Inbound List</li>
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
                            @empty(!session('id_inbound'))
                                <a href="{{ route('inbound_detail.index') }}" class="btn btn-info xs"><i class="fa fa-pencil">
                                        Active Transaction</i></a>
                            @endempty
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered inbound-table">
                            <thead>
                                <th class="text-center col-md-1">No</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Total item</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Discount</th>
                                <th class="text-center">Grandtotal</th>
                                <th class="text-center"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('inbound.supplier')
    @includeIf('inbound.detail')
@endsection

@push('scripts')
    <script>
        let table, table2;

        $(function() {
            table = $('.inbound-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('inbound.data') }}',
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
                        data: 'supplier'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'pay'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
            $('.supplier-table').DataTable()
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
                        data: 'purchase_price'
                    },
                    {
                        data: 'subtotal'
                    }
                ]
            })
        });


        function addForm() {
            $('#modal-supplier').modal('show');
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
