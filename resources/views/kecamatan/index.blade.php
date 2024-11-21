@extends ('layouts.app')

@section ('title', 'Data Kecamatan')

@section ('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-0">
        <div class="card-body">
          <a href="{{route('kecamatan.tambah')}}" class="btn btn-outline-primary mb-3 mr-2"> Tambah Data </a>
          <div class="table-responsive p-0">
            <table class="table table align-items-center mb-0 ">
              <thead>
                <tr>
                  <th style="width: 200px;" class="text-dark text-center text-sm font-weight-medium">No</th>
                  <th class="text-dark text-sm font-weight-medium px-6">Kecamatan</th>
                  <th class="text-center text-center text-dark text-sm font-weight-medium">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php ($no = 1)
                @foreach ($kecamatan as $row)
                <tr>
                  <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                  <td class="text-dark align-middle text-sm px-6">{{ $row -> namaKecamatan }}</td>
                  <td class="text-dark text-center align-middle text-center icon-lg">
                    <a href="{{route('kecamatan.edit', $row->id)}}">
                      <i class="fa-solid fa-edit btn-outline-success" style="margin-right: 5px;"></i>
                    </a>
                    <a href="{{route('kecamatan.hapus', $row->id)}}" id="delete">
                      <i class="fa-solid fa-trash btn-outline-danger"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection