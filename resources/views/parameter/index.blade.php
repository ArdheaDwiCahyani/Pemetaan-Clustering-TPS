@extends ('layouts.app')

@section ('title', 'Data Parameter')

@section ('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body">
          <a href="{{route('parameter.tambah')}}" class="btn btn-outline-primary mb-3 mr-2">Tambah Parameter</a>
          <div class="table-responsive p-0">
            <table class="table table align-items-center mb-0 ">
              <thead>
                <tr>
                  <th style="width: 200px;" class="text-dark text-center text-sm font-weight-medium">No</th>
                  <th class="text-dark text-sm font-weight-medium px-6">Nama Parameter</th>
                  <th class="text-center text-dark text-sm font-weight-medium">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php ($no = 1)
                @foreach ($parameter as $row)
                <tr>
                  <td class="text-dark text-center align-middle text-sm">{{ $no++ }}</td>
                  <td class="text-dark align-middle text-sm px-6">{{ $row->namaParameter }}</td>
                  <td class="text-center align-middle text-center icon-lg">
                    <a href="{{route('parameter.edit', $row->id)}}">
                      <i class="fa-solid fa-edit text-success" style="margin-right: 5px;"></i>
                    </a>
                    <a href="{{route('parameter.hapus', $row->id)}}" id="delete">
                      <i class="fa-solid fa-trash text-danger"></i>
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