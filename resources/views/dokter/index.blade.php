@extends('layout.app')

@section('title', 'Dashboard Dokter')

@section('nav-item')
    <li class="nav-item">
        <a href="{{ route('dokter.dashboard') }}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('periksa.index') }}" class="nav-link">
            <i class="nav-icon fas fa-stethoscope"></i>
            <p>
                Pemeriksaan
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('periksa.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daftar Pemeriksaan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('periksa.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tambah Pemeriksaan</p>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="/dokter/obat" class="nav-link">
            <i class="nav-icon fas fa-pills"></i>
            <p>Obat</p>
        </a>
    </li>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>Total Pemeriksaan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <a href="{{ route('periksa.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53</h3>
                            <p>Total Obat</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <a href="/dokter/obat" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
