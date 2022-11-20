<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Income Report</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/dist/css/adminlte.min.css') }}">
</head>

<body>
    <h3 class="text-center">Income Report</h3>
    <h4 class="text-center">Period: {{ indonesia_date($startDate, false) }} - {{ indonesia_date($endDate, false) }}</h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Date</th>
                <th class="text-center">Inbound</th>
                <th class="text-center">Sales</th>
                <th class="text-center">Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row as $col)
                        <td>{{ $col }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
