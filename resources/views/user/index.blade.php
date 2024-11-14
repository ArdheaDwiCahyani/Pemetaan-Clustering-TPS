@extends ('layouts.app')

@section ('title', 'Data User')

@section ('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body">
          <a href="#" class="btn btn-outline-primary mb-3 mr-2"> Tambah User </a>
          <a class="btn btn-primary bs-btn-active-bg mb-3" href="#"> Export PDF</a>
          <div class="table-responsive p-0">
            <table class="table table align-items-center mb-0 ">
              <thead>
                <tr>
                  <th style="width: 200px;" class="text-secondary text-center text-sm font-weight-medium">No</th>
                  <th class="text-secondary text-sm font-weight-medium text-center">Nama User</th>
                  <th class="text-secondary text-sm font-weight-medium text-center">Email</th>
                  <th class="text-center text-secondary text-sm font-weight-medium">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center align-middle text-sm">1</td>
                  <td class="text-center align-middle text-sm">Ardhea Dwi</td>
                  <td class="text-center align-middle text-sm">123@gmail.com</td>
                  <td class="align-middle text-center icon-lg">
                    <a href="#">
                      <i class="fa-solid fa-edit text-success" style="margin-right: 5px;"></i>
                    </a>
                    <a href="#">
                      <i class="fa-solid fa-trash text-danger"></i>
                    </a>
                </tr>
                <tr>
                  <td class="align-middle text-center text-sm">2</td>
                  <td class="align-middle text-center text-sm">Kamari</td>
                  <td class="align-middle text-center text-sm">Kamari@gmail.com</td>
                  <td class="align-middle text-center icon-lg">
                    <a href="#">
                      <i class="fa-solid fa-edit text-success" style="margin-right: 5px;"></i>
                    </a>
                    <a href="#">
                      <i class="fa-solid fa-trash text-danger"></i>
                    </a>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection