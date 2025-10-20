 <div class="row mb-3">
     <div class="col-lg-2">
         <label for="meassageInput" class="form-label">Rekening Belanja</label>
     </div>
     <div class="col-lg-10">
         <select id="subKategoriSelect" name="id_kode_rekening_belanja" class="form-select" data-choices
             data-choices-sorting="true">
             @php
                 $selectedId = old('id_kode_rekening_belanja', optional($rkbuBarangJasa)->id_kode_rekening_belanja);
             @endphp

             <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                 Pilih Kategori RKBU
             </option>

             @foreach ($rekening_belanja as $sub)
                 <option value="{{ $sub->id_kode_rekening_belanja }}"
                     {{ (string) $selectedId === (string) $sub->id_kode_rekening_belanja ? 'selected' : '' }}>
                     {{ $sub->kode_rekening_belanja }}.
                     {{ $sub->nama_rekening_belanja }}
                 </option>
             @endforeach
         </select>
     </div>
 </div>
 <div class="row mb-3">
     <div class="col-lg-2">
         <label for="meassageInput" class="form-label">User ID</label>
     </div>
     <div class="col-lg-10">
         <select id="subKategoriSelect" name="id_user" class="form-select" data-choices data-choices-sorting="true">
             @php
                 $selectedId = old('id_user', optional($rkbuBarangJasa)->id_user);
             @endphp

             <option value="" disabled {{ $selectedId ? '' : 'selected' }}>
                 Pilih Kategori RKBU
             </option>

             @foreach ($user as $sub)
                 <option value="{{ $sub->id_user }}"
                     {{ (string) $selectedId === (string) $sub->id_user ? 'selected' : '' }}>
                     {{ $sub->nama_lengkap }}
                 </option>
             @endforeach
         </select>
     </div>
 </div>
