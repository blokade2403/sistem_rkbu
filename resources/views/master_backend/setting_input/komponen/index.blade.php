@extends('layouts.main')
@section('content')
   <div class="card mb-5 mb-xxl-12">
        <div class="card-body pt-9 pb-0">
            @include('components.partials.table.headermenu')
            @include('master_backend.setting_input.komponen.modal_add')
        </div>
    </div>
    <div class="row gy-5 g-xl-8">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Members Statistics</span>
                    </h3>
                </div>
                <div class="card-body">
                    {{-- @include('components.partials.table.showentrysearch') --}}
                    <div class="table-responsive">
                            {!! $dataTable->table(['class' => 'table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4'], true) !!}
                         @include('master_backend.setting_input.komponen.modal_edit')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.partials.calender')
    @push('scripts')
        {!! $dataTable->scripts() !!}
    @endpush
@endsection
