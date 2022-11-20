<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Return Note PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }

        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }

        table.data {
            border-collapse: collapse;
        }

        .header {
            display: flex;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h1 class="text-center">Return</h1>
    <div class="header">
        <div class="text-right">{{ indonesia_date(date('Y-m-d')) }} / {{ date('H:i:s') }}</div>
        <img src="{{ public_path($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
    </div>
    <hr>
    <br>
    Dear,<br>
    @foreach ($customers as $customer)
        {{ $customer->name }}/{{ $customer->address }}<br>
        {{ $customer->phone }}
    @endforeach
    <br><br>
    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item->product->product_name }}</td>
                    <td class="text-right">{{ money_format($item->selling_price) }}</td>
                    <td class="text-right">{{ money_format($item->qty) }}</td>
                    <td class="text-right">{{ money_format($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><b>Total Price</b></td>
                <td class="text-right"><b>{{ money_format($retur->total_price) }}</b></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>Discount</b></td>
                <td class="text-right"><b>{{ money_format($retur->discount) }}%</b></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><b>Grandtotal</b></td>
                <td class="text-right"><b>{{ money_format($retur->pay) }}</b></td>
            </tr>
        </tfoot>
    </table>
    <br><br>
    <table width="100%">
        <tr>
            <td class="text-right">
                Regards,
                <br><br><br><br><br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
    <h5 class="text-right" style="bottom:0px;"><b>cs@sogsystem.com / 0895********* / sogsystem.com</b></h5>
</body>

</html>
