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
                    <div class="alert alert-success alert-dismissible">
                        <div class="i fa fa-check icon"></div>
                        Transaction has been finished.
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-warning" onclick="printNote('{{ route('retur.note') }}', 'Note PDF')">Reprint
                        Notes</button>
                    <a href="{{ route('retur.index') }}" class="btn btn-primary">New Transaction</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function printNote(url, title) {
            popupCenter(url, title, 900, 675)
        }

        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
            const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

            const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document
                .documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document
                .documentElement.clientHeight : screen.height;

            const systemZoom = width / window.screen.availWidth;
            const left = (width - w) / 2 / systemZoom + dualScreenLeft
            const top = (height - h) / 2 / systemZoom + dualScreenTop
            const newWindow = window.open(url, title,
                `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
            `
            );

            if (window.focus) newWindow.focus();
        }
    </script>
@endpush
