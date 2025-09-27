@extends('layouts.main')
@section('content')
    @include('partials.add_buttons', ['routePrefix' => $routePrefix])
    @include('rkbu.partials.selisihlebihrkbu')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div><!-- end card header -->
                @include('rkbu.partials.filter_rkbu', ['routePrefix' => $routePrefix])
                <div class="card-block">
                    <div class="card-body">
                        <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-hover table-centered align-middle table-nowrap mb-0'], true) !!}
                        </div>

                    </div>
                </div>
            </div> <!-- .card-->
        </div> <!-- .col-->
    </div> <!-- end row-->
    @push('scripts')
        {!! $dataTable->scripts() !!}
    @endpush
@endsection
