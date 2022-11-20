@extends('layouts.master')

@section('title')
    Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Supplier</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border mb-3">
                        <div class="btn-group">
                            <button class="btn btn-success xs" onclick="addForm('{{ route('supplier.store') }}')"><i
                                    class="fa fa-plus-circle"> Add</i></button>
                            <button class="btn btn-danger xs" onclick="deleteSelected()"><i class="fa fa-trash">
                                    Delete</i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <th class="col-md-1">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th class="text-center col-md-1">No</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Address</th>
                                <th class="text-center"><i class="fa fa-cog"></i></th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        {{-- <form action="" method="POST" class="form-customer">
                            @csrf
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('supplier.form')
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
                    url: '/supplier/data',
                },
                columns: [{
                        data: 'select_all'
                    }, {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'address'
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
            $('#modal-form .modal-title').text('Add Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name]').val(response.name);
                    $('#modal-form [name=phone]').val(response.phone);
                    $('#modal-form [name=address]').val(response.address);
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
                    $.post(url, $('.form-produk').serialize())
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
    </script>
@endpush
