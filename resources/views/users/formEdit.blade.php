@extends('layouts.app')

@section('title', 'Edit Data User')

@section('content')
    <form action="{{ route('user.tambah.update', $admins->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-0">
                        <div class="card-body pb-1">
                            <!-- Username -->
                            <div class="form-group mb-4">
                                <label for="name" class="text-dark text-sm font-weight-medium">Username</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $admins->name) }}" placeholder="Masukkan Username">
                            </div>

                            <!-- Email -->
                            <div class="form-group mb-4">
                                <label for="email" class="text-dark text-sm font-weight-medium">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $admins->email) }}" placeholder="Masukkan Email">
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-4">
                                <label for="password" class="text-dark text-sm font-weight-medium">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan Password Baru (kosongkan jika tidak ingin mengubah)">
                            </div>

                            <!-- Role -->
                            <div class="form-group mb-0">
                                <label for="role" class="text-dark text-sm font-weight-medium">Role</label>
                                <input type="text" class="form-control" id="role" name="role" value="{{ $admins->role }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="card-footer mt-0">
                            <button type="submit" class="btn btn-primary bs-btn-active-bg">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
