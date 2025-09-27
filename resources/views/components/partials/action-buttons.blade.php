<ul class="list-inline hstack gap-2 mb-0">
    @isset($editUrl)
        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title=""
            data-bs-original-title="Edit">
            <a href="{{ $editUrl }}" class="text-primary d-inline-block edit-item-btn">
                <i class="ri-pencil-fill fs-16"></i>
            </a>
        </li>
    @endisset
    @isset($deleteUrl)
        <form action="{{ $deleteUrl }}" method="POST" id="custom-sa-warning" class="delete-form d-inline">
            @csrf
            @method('DELETE')
            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                title="" data-bs-original-title="Remove">
                <button type="submit" class="text-danger d-inline-block remove-item-btn"
                    style="background: none; border: none; padding: 0; cursor: pointer;">
                    <i class="ri-delete-bin-5-fill fs-16"></i>
                </button>
            </li>
        </form>
    @endisset
</ul>
