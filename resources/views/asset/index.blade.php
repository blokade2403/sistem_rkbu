@extends('layouts.main')
@section('content')
    @include('partials.add_buttons_asset', ['routePrefix' => $routePrefix])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div><!-- end card header -->
                <div class="card-block">
                    @include('asset.filter_asset')
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
