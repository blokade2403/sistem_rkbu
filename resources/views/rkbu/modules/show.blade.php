@extends('layouts.main')

@section('content')
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">{{ $config['title'] }} - {{ $record->rkbu?->nomor_rkbu }}</h3>
            <div class="card-toolbar">
                <a href="{{ route('rkbu.' . $config['route'] . '.index') }}" class="btn btn-sm btn-light">Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-5">
                <span class="text-muted">Status RKBU:</span>
                <span class="badge badge-light-primary">{{ ucfirst($record->rkbu?->status ?? '-') }}</span>
            </div>
            <div class="row">
                @foreach ($config['fields'] as $field)
                    @php
                        $name = $field['name'];
                        $value = $record->{$name};
                    @endphp
                    @if ($field['type'] === 'file')
                        @if ($value)
                            <div class="col-md-6 mb-4">
                                <div class="text-muted">{{ $field['label'] }}</div>
                                <a href="{{ asset('storage/' . $value) }}" target="_blank" rel="noopener">Lihat Lampiran</a>
                            </div>
                        @endif
                    @else
                        <div class="col-md-6 mb-4">
                            <div class="text-muted">{{ $field['label'] }}</div>
                            <div>
                                @if (str_contains($name, 'anggaran') || in_array($name, ['harga_satuan', 'gaji_pokok', 'remunerasi', 'bpjs_kesehatan', 'bpjs_tk', 'bpjs_jht'], true))
                                    {{ $value === null ? '-' : 'Rp ' . number_format((float) $value, 2, ',', '.') }}
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @include('rkbu.partials.workflow_history', ['workflowInstances' => $record->rkbu?->workflowInstances ?? collect()])
            @include('rkbu.partials.uploaded_documents', ['detail' => $record])
        </div>
    </div>
@endsection
