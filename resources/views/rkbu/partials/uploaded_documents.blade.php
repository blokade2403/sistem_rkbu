@php
    $skipNumbers = $skipUploadNumbers ?? [];
    $uploadedDocuments = collect(range(1, 5))
        ->reject(fn ($number) => in_array($number, $skipNumbers, true))
        ->map(fn ($number) => [
            'number' => $number,
            'path' => $detail->{'upload_file_' . $number} ?? null,
            'label' => [1 => 'KAP', 2 => 'KAK', 3 => 'SPH'][$number] ?? 'Dokumen Pendukung ' . $number,
        ])
        ->filter(fn ($document) => filled($document['path']));
@endphp

@if ($uploadedDocuments->isNotEmpty())
    <div class="card card-bordered mt-6 mb-6">
        <div class="card-header min-h-60px">
            <h3 class="card-title fw-bolder">Dokumen Pendukung</h3>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach ($uploadedDocuments as $document)
                    <div class="col-md-6 col-xl-4">
                        <a href="{{ asset('storage/' . $document['path']) }}" target="_blank" rel="noopener noreferrer"
                            class="d-flex align-items-center border border-gray-300 rounded p-4 text-hover-primary">
                            <span class="symbol symbol-40px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="fas fa-file-alt text-primary"></i>
                                </span>
                            </span>
                            <span class="d-flex flex-column overflow-hidden">
                                <span class="fw-bolder text-gray-800">{{ $document['label'] }}</span>
                                <span class="text-muted fs-8 text-truncate">{{ basename($document['path']) }}</span>
                                <span class="text-primary fs-8">Buka dokumen</span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
