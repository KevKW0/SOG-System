@extends('layouts.master')

@section('title')
    Income Report {{ indonesia_date($startDate, false) }} - {{ indonesia_date($endDate, false) }}
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Report</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriod()" class="btn btn-info btn-sm"><i class="fa fa-plus-circle"></i>
                        Change Period</button>
                    <a href="{{ route('report.export_pdf', [$startDate, $endDate]) }}" class="btn btn-success btn-sm"><i
                            class="fa fa-file-pdf"></i>
                        Export PDF</a>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th>No</th>
                            <th>Date</th>
                            <th>Inbound</th>
                            <th>Sales</th>
                            <th>Income</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @includeIf('report.form')
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
                    url: '{{ route('report.data', [$startDate, $endDate]) }}',
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
                        data: 'inbound'
                    },
                    {
                        data: 'sales'
                    },
                    {
                        data: 'income'
                    },
                ],
                dom: 'rt',
                bSort: false,
                bPaginate: false
            });

            $('.datepicker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10)
            });
        });

        function updatePeriod(url) {
            $('#modal-form').modal('show');
        }
    </script>
@endpush
