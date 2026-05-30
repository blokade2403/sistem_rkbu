<ul class="list-inline hstack gap-2 mb-0">
    @isset($editUrl)
        @if (($editType ?? 'route') === 'route')
            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                <a href="{{ $editUrl }}" class="text-primary">
                    <i class="ri-pencil-fill fs-16"></i>
                </a>
            </li>
        @endif
    @endisset


    @isset($editData)
        @if (($editType ?? '') === 'modal')
            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                <button type="button" class="text-primary btn-edit" style="background:none;border:none;padding:0"
                    data-bs-toggle="modal" data-bs-target="#createModal" data-id="{{ $editData->id }}"
                    data-nama="{{ $editData->nama_jabatan ?? '' }}" data-parent="{{ $editData->parent_id ?? '' }}"
                    data-unit="{{ $editData->unit_id ?? '' }}">
                    <i class="ri-pencil-fill fs-16"></i>
                </button>
            </li>
        @endif
    @endisset

    @isset($deleteUrl)
        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
            <form action="{{ $deleteUrl }}" method="POST" class="delete-form d-inline">
                @csrf
                @method('DELETE')

                <button type="submit" class="text-danger" style="background:none;border:none;padding:0">
                    <i class="ri-delete-bin-5-fill fs-16"></i>
                </button>
            </form>
        </li>
    @endisset
</ul>
