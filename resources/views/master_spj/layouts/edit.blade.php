@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            @include('master_spj.layouts.detail_komponen_edit', ['routePrefix' => 'master_spjs'])
        </div>

    </div>
@endsection
