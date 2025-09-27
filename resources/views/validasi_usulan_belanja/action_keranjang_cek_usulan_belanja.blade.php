 <div class="col-sm-12 invoice-btn-group text-center">
     <div class="mt-2">
         @if ($no_inv->status_permintaan_barang == 'Proses Pesanan Barang')
             <button type="submit" class="btn btn-primary btn-outline-success" data-toggle="tooltip" data-placement="top"
                 title="" data-original-title="Kirim Usulan Barang"><i
                     class="icofont icofont-chart-histogram"></i></i>Kirim
                 Usulan</button>
             <a href="{{ route($routePrefix . '.index') }}" class="btn btn-danger btn-outline-danger">Kembali</a>
         @else
             <button type="submit" class="btn btn-primary btn-outline-success" data-toggle="tooltip"
                 data-placement="top" title="" data-original-title="Kirim Usulan Barang"><i
                     class="icofont icofont-chart-histogram"></i></i>Kirim Usulan</button>
             <a href="{{ route($routePrefix . '.index') }}" class="btn btn-outline-secondary">Kembali</a>
         @endif
         </form>
     </div>
