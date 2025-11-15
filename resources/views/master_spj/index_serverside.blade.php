@extends('layouts.main')
@section('content')
    <div class="row">
        <!-- Basic row reorder table start -->
        <div class="card">
            <div class="card-header">
                <div class="col-xl-12">
                    @include('partials.animate_progress_form')
                    {{-- @include('rkbu.partials.filter_rkbu', ['routePrefix' => $routePrefix]) --}}
                    <div class="card-block">
                        <div class="card-body">
                            <div class="table-responsive">
                                {!! $dataTable->table(['id' => 'masterspj-table', 'class' => 'table ...'], true) !!}
                                {{-- {!! $dataTable->table(['class' => 'table table-hover table-centered align-middle table-nowrap mb-0'], true) !!} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        {!! $dataTable->scripts() !!}
        <script>
            // 🔁 Refresh datatable saat filter berubah
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                $('#masterspj-table').DataTable().ajax.reload();
            });
        </script>
    @endpush
@endsection
