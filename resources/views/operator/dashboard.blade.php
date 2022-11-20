@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body text-center">
                        <h1>Welcome, {{ auth()->user()->name }}</h1>
                        <h2>You are logged in as Operator</h2>
                        <br><br>
                        <a href="{{ route('sales.index') }}" class="btn btn-success btn-lg">New Transaction</a>
                        <br><br>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.container-fluid -->
@endsection
