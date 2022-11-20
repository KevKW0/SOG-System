@extends('layouts.master')

@section('title')
    Setting
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Setting</li>
@endsection

@section('content')
    <hr class="mb-5">
    <div class="row col-lg-12">
        <div class="container">
            <div class="alert alert-success alert-dismissible" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i>Changes Saved Successfully
            </div>
            <form action="{{ route('setting.update') }}" method="POST" class="form-setting" data-toggle="validator"
                enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group row">
                        <label for="company_name" class="col-lg-2 col-lg-offset-1 control-label">Company Name</label>
                        <div class="col-lg-8">
                            <input type="text" name="company_name" id="company_name" class="form-control" required
                                autofocus>
                            <span class="help-block with-errors" style="color: red"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-lg-2 col-lg-offset-1 control-label">Phone</label>
                        <div class="col-lg-8">
                            <input type="text" name="phone" id="phone" class="form-control" required>
                            <span class="help-block with-errors" style="color: red"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-lg-2 col-lg-offset-1 col-md-offset-1 control-label">Address</label>
                        <div class="col-lg-8">
                            <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
                            <span class="help-block with-errors" style="color: red"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="path_logo" class="col-lg-2 col-lg-offset-1 control-label">Company Logo</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="file" name="path_logo" id="path_logo"
                                onchange="preview('.show-logo', this.files[0])">
                            <span class="help-block with-errors" style="color: red"></span>
                            <br>
                            <div class="show-logo"></div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            showData()

            $('.form-setting').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-setting').attr('action'),
                            type: $('.form-setting').attr('method'),
                            data: new FormData($('.form-setting')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            showData();
                            $('.alert').fadeIn()

                            setTimeout(() => {
                                $('.alert').fadeOut();
                            }, 3000);
                        })
                        .fail(errors => {
                            alert('Data Cannot Be Saved')
                            return
                        })
                }
            })
        })

        function showData() {
            $.get('{{ route('setting.show') }}')
                .done(response => {
                    $('[name=company_name]').val(response.company_name)
                    $('[name=phone]').val(response.phone)
                    $('[name=address]').val(response.address)
                    $('title').text(response.company_name + ' | Setting');
                    $('.company-name').text(response.company_name)

                    $('.show-logo').html(`<img src="{{ url('/') }}${response.path_logo}" width="200">`)
                    $('[rel=icon]').attr('href', `{{ url('/') }}/${response.path_logo}`)
                })
                .fail(response => {
                    alert('Data Cannot be Displayed')
                    return
                })
        }
    </script>
@endpush
