@extends('layouts.master')

@section('title')
    Product
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Product</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border mb-3">
                        <div class="btn-group">
                            <button class="btn btn-success xs" onclick="addForm('{{ route('product.store') }}')"><i
                                    class="fa fa-plus-circle"> Add</i></button>
                            <button class="btn btn-danger xs"
                                onclick="deleteSelected('{{ route('product.delete_selected') }}')"><i class="fa fa-trash">
                                    Delete</i></button>
                            <button class="btn btn-info xs"
                                onclick="printBarcode('{{ route('product.print_barcode') }}')"><i class="fa fa-barcode">
                                    Print Barcode</i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <form action="" method="post" class="form-product">
                            @csrf
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>
                                        <input type="checkbox" name="select_all" id="select_all">
                                    </th>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Merk</th>
                                    <th class="text-center">Purchase Price</th>
                                    <th class="text-center">Selling Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center"><i class="fa fa-cog"></i></th>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('product.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('product.data') }}',
                },
                columns: [{
                        data: 'select_all',
                        searchable: false,
                        sortable: false
                    }, {
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
                        data: 'category_name'
                    },
                    {
                        data: 'merk'
                    },
                    {
                        data: 'purchase_price'
                    },
                    {
                        data: 'selling_price'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $('#modal-form').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Data Cannot Be Saved');
                            return;
                        });
                }
            });

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            });
        });


        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Add Product');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=product_name]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Product');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=product_name]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=product_name]').val(response.product_name);
                    $('#modal-form [name=product_code]').val(response.product_code);
                    $('#modal-form [name=id_category]').val(response.id_category);
                    $('#modal-form [name=merk]').val(response.merk);
                    $('#modal-form [name=purchase_price]').val(response.purchase_price);
                    $('#modal-form [name=selling_price]').val(response.selling_price);
                    $('#modal-form [name=discount]').val(response.discount);
                    $('#modal-form [name=stock]').val(response.stock);
                })
                .fail((errors) => {
                    alert('Data Cannot Be Displayed');
                    return;
                });
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

        function deleteSelected(url) {
            if ($('input:checked').length > 1) {
                if (confirm('Are you sure want to delete selected data?')) {
                    $.post(url, $('.form-product').serialize())
                        .done((response) => {
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Cannot Delete the Data');
                            return;
                        });
                }
            } else {
                alert('Select Data to be Deleted');
                return;
            }
        }

        function printBarcode(url) {
            if ($('input:checked').length < 1) {
                alert('Select Data Data to be Printed');
                return;
            } else if ($('input:checked').length < 3) {
                alert('Select Data Minimum 3 Data to be Print');
                return;
            } else {
                $('.form-product')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        }
    </script>
@endpush
