<x-layouts.main class="main">
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-header start -->
                <x-partials.breadcrumb>
                </x-partials.breadcrumb>
                <!-- Page-header end -->

                <!-- Page body start -->
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Basic Form Inputs card start -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-header-right">
                                        <i class="icofont icofont-spinner-alt-5"></i>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <h4 class="sub-title">Form Inputs</h4>
                                    <form id="confirmSubmitForm" method="POST"
                                        action="{{ route('users.update', $user->id_user) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Username</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="username" value="{{ $user->username }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nama_lengkap" value="{{ $user->username }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">NIP User</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="nip_user" value="{{ $user->nip_user }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="email" value="{{ $user->email }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Unit</label>
                                            <div class="col-sm-10">
                                                <select name="id_unit" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_unit }}">{{ $user->unit->nama_unit }}
                                                    </option>
                                                    @foreach ($units as $key)
                                                        <option value="{{ $key->id_unit }}">
                                                            {{ $key->nama_unit }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Level User</label>
                                            <div class="col-sm-10">
                                                <select name="id_level" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_level }}">
                                                        {{ $user->levelUser->nama_level_user }}
                                                    </option>
                                                    @foreach ($level_users as $key)
                                                        <option value="{{ $key->id_level }}">
                                                            {{ $key->nama_level_user }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Fase User</label>
                                            <div class="col-sm-10">
                                                <select name="id_fase" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_fase }}">{{ $user->fase->nama_fase }}
                                                    </option>
                                                    @foreach ($fases as $key)
                                                        <option value="{{ $key->id_fase }}">
                                                            {{ $key->nama_fase }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">KSP</label>
                                            <div class="col-sm-10">
                                                <select name="id_ksp" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_ksp }}">{{ $user->ksp->nama_ksp }}
                                                    </option>
                                                    @foreach ($ksps as $key)
                                                        <option value="{{ $key->id_ksp }}">
                                                            {{ $key->nama_ksp }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pejabat</label>
                                            <div class="col-sm-10">
                                                <select name="id_pejabat" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_pejabat }}">
                                                        {{ $user->pejabat->nama_pejabat }}
                                                    </option>
                                                    @foreach ($pejabats as $key)
                                                        <option value="{{ $key->id_pejabat }}">
                                                            {{ $key->nama_pejabat }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Pendukung PPK</label>
                                            <div class="col-sm-10">
                                                <select name="id_admin_pendukung_ppk"
                                                    class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_admin_pendukung_ppk }}">
                                                        {{ $user->nama_pendukung_ppk }}
                                                    </option>
                                                    @foreach ($admin_pendukungs as $key)
                                                        <option value="{{ $key->id_admin_pendukung_ppk }}">
                                                            {{ $key->nama_pendukung_ppk }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PPTK</label>
                                            <div class="col-sm-10">
                                                <select name="id_pptk" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->id_pptk }}">
                                                        {{ $user->pptks->nama_pptk ?? '' }}
                                                    </option>
                                                    <option value="">Tidak Tagging</option>
                                                    @foreach ($pptks as $key)
                                                        <option value="{{ $key->id_pptk }}">
                                                            {{ $key->nama_pptk }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status User</label>
                                            <div class="col-sm-10">
                                                <select name="status_user" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->status_user }}">{{ $user->status_user }}
                                                    </option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak aktif">Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status Edit</label>
                                            <div class="col-sm-10">
                                                <select name="status_edit" class="js-example-basic-single col-sm-12">
                                                    <option value="{{ $user->status_edit }}">{{ $user->status_edit }}
                                                    </option>
                                                    <option value="aktif">Aktif</option>
                                                    <option value="tidak aktif">Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="card-block card-header-right">
                                            <a href="{{ route('users.index') }}"
                                                class="btn btn-sm btn-secondary btn-outline-secondary me-4">
                                                <i class="icofont icofont-circled-left"></i> Kembali
                                            </a>
                                            <button class="btn btn-sm btn-primary btn-outline-primary me-4">
                                                <i class="icofont icofont-check-circled"></i> Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Basic Form Inputs card end -->
                        </div>
                    </div>
                </div>
                <!-- Page body end -->
            </div>
        </div>
    </div>

</x-layouts.main>
