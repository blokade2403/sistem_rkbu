@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="horizontal-form-showcode" class="form-label text-muted">Hide</label>
                            <input class="form-check-input code-switcher" type="checkbox" id="horizontal-form-showcode">
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                        <form id="confirmSubmitForm" method="POST" action="{{ route($routePrefix . '.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="nameInput" class="form-label">Username</label>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" name="username" class="form-control" id="nameInput"
                                        placeholder="Enter your username">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="nameInput" class="form-label">Nama Lengkap</label>
                                </div>
                                <div class="col-lg-10">
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        placeholder="Enter your name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="nameInput" class="form-label">NIP/NRP User</label>
                                </div>
                                <div class="col-lg-10">
                                    <input type="number" name="nip_user" class="form-control" placeholder="Enter your nip">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="nameInput" class="form-label">Email</label>
                                </div>
                                <div class="col-lg-10">
                                    <input type="number" name="email" class="form-control" placeholder="Enter your name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="nameInput" class="form-label">Password</label>
                                </div>
                                <div class="col-lg-10">
                                    <input type="password" class="form-control" name="password" id="inputPassword4"
                                        placeholder="Password">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-2">
                                    <label for="nameInput" class="form-label">Password</label>
                                </div>
                                <div class="col-lg-10">
                                    <select id="form-control choices-select" class="form-select" data-choices
                                        data-choices-sorting="true">
                                        <option selected>Choose...</option>
                                        <option>...</option>
                                    </select>
                                </div>
                            </div>

                            @include('partials.submit_buttons', ['routePrefix' => $routePrefix])
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- .col-->
    </div> <!-- end row-->
@endsection
