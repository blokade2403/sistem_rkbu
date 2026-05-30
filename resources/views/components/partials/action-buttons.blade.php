@props(['item' => null, 'editUrl' => null, 'deleteUrl' => null])

<ul class="list-inline hstack gap-2 mb-0">

    {{-- EDIT --}}
    @if ($item)
        <li class="list-inline-item">
            <a href="javascript:void(0)" class="text-primary d-inline-block btn-edit" data-id="{{ $item->id }}"
                data-nama="{{ $item->nama_unit }}">
                <i class="ri-pencil-fill fs-16"></i>
            </a>
        </li>
    @elseif ($editUrl)
        <li class="list-inline-item">
            <a href="{{ $editUrl }}" class="text-primary d-inline-block">
                <i class="ri-pencil-fill fs-16"></i>
            </a>
        </li>
    @endif

    {{-- DELETE --}}
    @if ($deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST" class="delete-form mb-0">
            @csrf
            @method('DELETE')
            <li class="list-inline-item">
                <button type="submit" class="text-danger" style="background: none; border: none;">
                    <i class="ri-delete-bin-5-fill fs-16"></i>
                </button>
            </li>
        </form>
    @endif

</ul>
