  <a href="{{ route($routePrefix . '.edit', $item->id_usulan_barang_detail) }}" class="m-r-15 text-info"
      data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i
          class="icofont icofont-ui-edit"></i></a>
  @if (session('level_user') == 'Administrator')
      <form action="{{ route($routePrefix . '.delete_detail', $item->id_usulan_barang_detail) }}" method="POST"
          class="delete-form mb-0">
          @csrf
          @method('DELETE')
          <button type="submit" class="text-danger"
              style="background: none; border: none; padding: 0; cursor: pointer;" data-toggle="tooltip"
              data-placement="top" title="" data-original-title="Delete"><i
                  class="icofont icofont-delete-alt"></i></button>
      </form>
  @endif


  {{-- {{ route($routePrefix . '.delete_detail', $item->id_usulan_barang_detail) }} --}}
