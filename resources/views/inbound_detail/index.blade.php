@extends('layouts.master')

@section('title')
    Inbound Transcation
@endsection

@push('css')
    <style>
        .show-total {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .show-counted {
            padding: 10px;
            background: #f0f0f0;
        }

        .inbound-table tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .show-total {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endpush

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Inbound Transcation</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border mb-3">
                        <table>
                            <tr>
                                <td>Supplier</td>
                                <td>: {{ $suppliers->name }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>: {{ $suppliers->phone }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>: {{ $suppliers->address }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-body">
                        <form action="" class="form-product">
                            @csrf
                            <div class="form-group row">
                                <label for="product_code" class="col-lg-1"> Product Code</label>
                                <div class="col-lg-5">
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="id_inbound" id="id_inbound" value="{{ $id_inbound }}">
                                        <input type="hidden" name="id_product" id="id_product">
                                        <input type="text" class="form-control" name="product_code" id="product_code"
                                            placeholder="Choose Product Code" aria-describedby="button-addon2">
                                        <button onclick="showProducts()" class="btn btn-info" type="button"
                                            id="button-addon2"><i class="fa fa-arrow-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped table-bordered inbound-table">
                            <thead>
                                <th class="text-center col-md-1">No</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center col-md-1">Qty</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="show-total bg-primary"></div>
                                <div class="show-counted"></div>
                            </div>
                            <div class="col-lg-4">
                                <form action="{{ route('inbound.store') }}" class="form-inbound" method="post">
                                    @csrf
                                    <input type="hidden" name="id_inbound" value="{{ $id_inbound }}">
                                    <input type="hidden" name="total_price" id="total_price">
                                    <input type="hidden" name="total_item" id="total_item">
                                    <input type="hidden" name="pay" id="pay">

                                    <div class="form-group row">
                                        <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="totalrp" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="discount" class="col-lg-2 control-label">Discount(%)</label>
                                        <div class="col-lg-8">
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ $discount }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="payrp" class="col-lg-2 control-label">Grandtotal</label>
                                        <div class="col-lg-8">
                                            <input type="text" id="payrp" class="form-control" readonly>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-primary btn-sm justify-content-md-end btn-save"><i
                                class="fa fa-floppy-o"></i> Save Transaction</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('inbound_detail.product')
@endsection

@push('scripts')
    <script>
        let table, table2;

        // Memanggil Data Table Inbound
        $(function() {
            table = $('.inbound-table').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('inbound_detail.data', $id_inbound) }}',
                    },
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
                            data: 'purchase_price'
                        },
                        {
                            data: 'qty'
                        },
                        {
                            data: 'subtotal'
                        },
                        {
                            data: 'action',
                            searchable: false,
                            sortable: false
                        },
                    ],
                    dom: 'rt',
                    bSort: false,
                    paginate: false
                })
                .on('draw.dt', function() {
                    loadForm($('#discount').val())
                });

            // Memanggil Data Table Product
            table2 = $('.product-table').DataTable()

            // Edit Value QTY
            $(document).on('input', '.qty', function() {
                let id = $(this).data('id')
                let qty = parseInt($(this).val())

                if (qty < 1) {
                    alert(`Quantity can't be less than 1`)
                    $(this).val(1)
                    return
                }

                if (qty > 10000) {
                    alert(`Quantity can't be more than 10.000`)
                    $(this).val(10000)
                    return
                }

                $.post(`{{ url('/inbound_detail') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'qty': qty
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            table.ajax.reload()
                        })
                    })
                    .fail(errors => {
                        alert(`Can't save Data`)
                        return
                    })
            })
            // Edit Value Discount
            $(document).on('input', '#discount', function() {
                if ($(this).val() == "") {
                    $(this).val(0).select()
                }

                loadForm($(this).val())
            })

            // Menyimpan Transaksi
            $('.btn-save').on('click', function() {
                $('.form-inbound').submit()
            })
        });

        // Menampilkan Modal Product
        function showProducts() {
            $('#modal-product').modal('show');
        }

        // Menghilangkan modal Product
        function hideProducts() {
            $('#modal-product').modal('hide');
        }

        // Memilih Product
        function chooseProducts(id, code) {
            $('#id_product').val(id)
            $('#product_code').val(code)
            hideProducts()
            addProduct()
        }

        // Menambah Product ke Inbound Detail
        function addProduct() {
            $.post('{{ route('inbound_detail.store') }}', $('.form-product').serialize())
                .done(response => {
                    $('#product_code').focus()
                    table.ajax.reload()
                })
                .fail(errors => {
                    alert(`Can't save Data`)
                })
        }

        // Delete Data
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

        // Menampilkan Preview Data
        function loadForm(discount = 0) {
            $('#total_price').val($('.total_price').text())
            $('#total_item').val($('.total_item').text())

            $.get(`{{ url('/inbound_detail/loadform') }}/${discount}/${$('.total_price').text()}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp)
                    $('#payrp').val('Rp. ' + response.payrp)
                    $('#pay').val(response.pay)
                    $('.show-total').text('Rp. ' + response.payrp)
                    $('.show-counted').text('Rp. ' + response.counted)
                })
                .fail(errors => {
                    alert('Unable to Show Data');
                    return;
                })
        }
    </script>
@endpush
