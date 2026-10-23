@if (isset($workflowInstances) && $workflowInstances->isNotEmpty())
    <div class="card card-bordered mt-6 mb-6">
        <div class="card-header min-h-60px">
            <h3 class="card-title fw-bolder">Riwayat Approval dan Keterangan</h3>
        </div>
        <div class="card-body">
            @foreach ($workflowInstances as $instanceIndex => $instance)
                <div class="mb-8 {{ !$loop->last ? 'pb-6 border-bottom border-gray-300' : '' }}">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div class="fw-bolder text-gray-900">Pengajuan {{ $instanceIndex + 1 }}</div>
                        <span class="text-muted fs-7">{{ $instance->submitted_at?->format('d-m-Y H:i') ?? $instance->created_at?->format('d-m-Y H:i') ?? '-' }}</span>
                    </div>

                    @forelse ($instance->approvals->sortBy('sequence') as $approval)
                        @php
                            $statusInfo = [
                                'pending' => ['Menunggu', 'primary'],
                                'approved' => ['Disetujui', 'success'],
                                'revision' => ['Revisi', 'warning'],
                                'rejected' => ['Ditolak', 'danger'],
                            ][strtolower($approval->status)] ?? [ucfirst($approval->status), 'secondary'];
                            $validatorName = $approval->validatorUser?->nama
                                ?? $approval->position?->nama_jabatan
                                ?? $approval->workflowStep?->position?->nama_jabatan
                                ?? $approval->workflowStep?->validatorRole?->nama_validator
                                ?? 'Validator';
                            $stepName = $approval->workflowStep?->position?->nama_jabatan
                                ?? $approval->workflowStep?->validatorRole?->nama_validator
                                ?? ('Tahap ' . ($approval->sequence ?? $loop->iteration));
                            $logs = $approval->logs->sortBy(fn ($log) => $log->aksi_at ?? $log->created_at);
                            $hasLogComment = $logs->contains(fn ($log) => filled($log->catatan));
                        @endphp

                        <div class="border border-gray-300 rounded p-4 mb-4">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                <span class="badge badge-light-info">{{ $stepName }}</span>
                                <span class="fw-bold">{{ $validatorName }}</span>
                                <span class="badge badge-light-{{ $statusInfo[1] }}">{{ $statusInfo[0] }}</span>
                                @if ($approval->approved_at ?? $approval->closed_at)
                                    <span class="text-muted fs-8 ms-auto">{{ ($approval->approved_at ?? $approval->closed_at)->format('d-m-Y H:i') }}</span>
                                @endif
                            </div>

                            @forelse ($logs as $log)
                                @php
                                    $actionLabel = [
                                        'submit' => 'Diajukan',
                                        'approved' => 'Disetujui',
                                        'revision' => 'Diminta revisi',
                                        'rejected' => 'Ditolak',
                                        'delegated' => 'Didelegasikan',
                                        'cancel' => 'Dibatalkan',
                                    ][$log->aksi] ?? ucfirst($log->aksi);
                                    $actor = $log->user?->nama ?? $log->position?->nama_jabatan ?? 'Pengguna';
                                @endphp
                                <div class="mb-3 ps-4 border-start border-3 border-gray-300">
                                    <div class="d-flex flex-wrap justify-content-between gap-2">
                                        <span class="fw-bold text-gray-700">{{ $actionLabel }} · {{ $actor }}</span>
                                        <span class="text-muted fs-8">{{ $log->aksi_at?->format('d-m-Y H:i') ?? $log->created_at?->format('d-m-Y H:i') ?? '-' }}</span>
                                    </div>
                                    @if (filled($log->catatan))
                                        <div class="text-gray-700 mt-2">{{ $log->catatan }}</div>
                                    @endif
                                </div>
                            @empty
                                @if (filled($approval->catatan))
                                    <div class="text-gray-700">{{ $approval->catatan }}</div>
                                @else
                                    <div class="text-muted fs-7">Belum ada keterangan untuk tahap ini.</div>
                                @endif
                            @endforelse

                            @if (!$hasLogComment && filled($approval->catatan))
                                <div class="alert alert-light-warning mb-0 mt-3">
                                    <span class="fw-bold">Keterangan approval:</span> {{ $approval->catatan }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted">Belum ada tahapan approval pada pengajuan ini.</div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
@endif
