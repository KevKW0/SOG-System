@extends('layouts.master')

@section('title')
    Edit Profile
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Edit Profile</li>
@endsection

@section('content')
    <hr class="mb-5">
    <div class="row col-lg-12">
        <div class="container">
            <div class="alert alert-success alert-dismissible" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-check"></i>Changes Saved Successfully
            </div>
            <form action="{{ route('user.update_profile') }}" method="POST" class="form-profile" data-toggle="validator"
                enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group row">
                        <label for="name" class="col-lg-2 col-lg-offset-1 control-label">Name</label>
                        <div class="col-lg-8">
                            <input type="text" name="name" id="name" class="form-control" required autofocus
                                value="{{ $profile->name }}">
                            <span class="help-block with-errors" style="color: red"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-lg-2 col-lg-offset-1 control-label">Profile Picture</label>
                        <div class="col-lg-8">
                            <input class="form-control" type="file" name="photo" id="photo"
                                onchange="preview('.show-picture', this.files[0])">
                            <span class="help-block with-errors" style="color: red"></span>
                            <br>
                            <div class="show-picture">
                                <img src="{{ url($profile->photo ?? '/') }}" width="200">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="old_password" class="col-lg-2 col-md-offset-1 control-label">Old Password</label>
                        <div class="col-lg-8">
                            <input type="password" name="old_password" id="old_password" class="form-control"
                                minlength="8">
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-2 col-md-offset-1 control-label">New Password</label>
                        <div class="col-lg-8">
                            <input type="password" name="password" id="password" class="form-control" minlength="8">
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-lg-2 col-md-offset-1 control-label">Confirm
                            Password</label>
                        <div class="col-lg-8">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" data-match="#password">
                            <span class="help-block with-errors" style="color:red;"></span>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                </div>
        </div>
        </form>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#old_password').on('keyup', function() {
                if ($(this).val() != "") {
                    $('#password, #password_confirmation').attr('required', true)
                } else {
                    $('#password, #password_confirmation').attr('required', false)
                }
            })

            $('.form-profile').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-profile').attr('action'),
                            type: $('.form-profile').attr('method'),
                            data: new FormData($('.form-profile')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            $('[name=name]').val(response.name)
                            $('.show-picture').html(
                                `<img src="{{ url('/') }}${response.photo}" width="200">`)
                            $('.img-profile').attr('src', `{{ url('/') }}/${response.photo}`)

                            $('.alert').fadeIn()

                            setTimeout(() => {
                                $('.alert').fadeOut();
                            }, 3000);
                        })
                        .fail(errors => {
                            if (errors.status == 422) {
                                alert(errors.responseJSON)
                            } else {
                                alert('Data Cannot Be Saved')
                            }
                            return
                        })
                }
            })
        })
    </script>
@endpush
