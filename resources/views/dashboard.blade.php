@extends ('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Data Kecamatan</p>
                                <h5 class="font-weight-bolder">
                                    {{ $jmlKecamatan }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <div class="icon icon-md2 bg-gradient-primary text-center rounded-circle">
                                <i class="bi bi-map-fill text-light text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Data Kelurahan</p>
                                <h5 class="font-weight-bolder">
                                    {{ $jmlKelurahan }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <div class="icon icon-md2 bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="bi bi-geo-fill text-light text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Data Parameter</p>
                                <h5 class="font-weight-bolder">
                                    {{ $jmlParameter }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <div class="icon icon-md2 bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="bi bi-box-fill text-light text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Data TPS</p>
                                <h5 class="font-weight-bolder">
                                    {{ $jmlTps }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <div class="icon icon-md2 bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="bi bi-database-fill text-light text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Data Jarak</p>
                                <h5 class="font-weight-bolder">
                                    {{ $jmlJarak }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <div class="icon icon-md2 bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="bi bi-clipboard-data-fill text-light text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Data Sampah</p>
                                <h5 class="font-weight-bolder">
                                    {{ $jmlSampah }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <div class="icon icon-md2 bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="bi bi-table text-light text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection