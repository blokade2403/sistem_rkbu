 <div class="card">
     <div class="card-header">
         <h4 class="sub-title">Proses Barang Datang</h4>
         <div class="card-header-right">
             <ul class="list-unstyled card-option">
                 <li><i class="feather icon-maximize full-card"></i></li>
                 <li><i class="feather icon-minus minimize-card"></i></li>
             </ul>
         </div>
     </div>
     <div class="card-block">
         <div class="j-wrapper j-wrapper-940">
             {{-- <form class="needs-validation" method="POST"
                 action="{{ route('master_spj.updateBarangDatang', $master_spj->id_master_spj) }}"
                 id="confirmSubmitForm" enctype="multipart/form-data" validate> --}}

             <form action="j-pro/php/action.php" method="POST" class="j-pro" id="j-pro"
                 enctype="multipart/form-data" novalidate="">
                 @csrf
                 @method('PUT')
                 <!-- end /.header-->
                 <div class="j-content">
                     <!-- start country -->
                     <div class="j-unit">
                         <label class="j-input j-select">
                             <select name="status_proses_pengiriman_barang">
                                 <option value="" selected="">Pilih Status Barang Datang</option>
                                 <option value="Selesai">Selesai</option>
                                 <option value="Parsial 1">Parsial 1</option>
                                 <option value="Parsial 2">Parsial 2</option>
                                 <option value="Parsial 3">Parsial 3</option>
                             </select>
                         </label>
                     </div>
                     <!-- end country -->
                     <div class="j-divider j-gap-bottom-25"></div>
                     <!-- start subject -->
                     <div class="j-unit">
                         <div class="j-input">
                             <label class="j-icon-left" for="subject">
                                 <i class="icofont icofont-ui-check"></i>
                             </label>
                             <input type="text" id="subject" name="subject" placeholder="Subject">
                         </div>
                     </div>
                     <!-- end subject -->
                     <!-- start message -->
                     <div class="j-unit">
                         <div class="j-input">
                             <label class="j-icon-left" for="message">
                                 <i class="icofont icofont-file-text"></i>
                             </label>
                             <textarea placeholder="Isi Keterangan Verifikasi" spellcheck="false" id="message" name="keterangan_barang_datang">{{ $master_spj->keterangan_barang_datang }}</textarea>
                             <span class="j-tooltip j-tooltip-right-top">Isi Keterangan Verfikasi Penerimaan
                                 Barang</span>
                         </div>
                     </div>
                     <!-- end message -->
                     <!-- start file -->
                     <div class="j-unit">
                         <div class="j-input j-append-big-btn">
                             <label class="j-icon-left" for="file_input">
                                 <i class="icofont icofont-download"></i>
                             </label>
                             <div class="j-file-button">
                                 Browse
                                 <input type="file" name="foto_barang_datang"
                                     onchange="document.getElementById('file_input').value = this.value;">
                             </div>
                             <input type="text" id="file_input" readonly="" placeholder="no file selected">
                         </div>
                     </div>
                     <div class="j-unit">
                         <div class="j-input j-append-big-btn">
                             <label class="j-icon-left" for="file_input">
                                 <i class="icofont icofont-download"></i>
                             </label>
                             <div class="j-file-button">
                                 Browse
                                 <input type="file" name="foto_barang_datang_2"
                                     onchange="document.getElementById('file_input').value = this.value;">
                             </div>
                             <input type="text" id="file_input" readonly="" placeholder="no file selected">
                         </div>
                     </div>
                     <div class="j-unit">
                         <div class="j-input j-append-big-btn">
                             <label class="j-icon-left" for="file_input">
                                 <i class="icofont icofont-download"></i>
                             </label>
                             <div class="j-file-button">
                                 Browse
                                 <input type="file" name="foto_barang_datang_3"
                                     onchange="document.getElementById('file_input').value = this.value;">
                             </div>
                             <input type="text" id="file_input" readonly="" placeholder="no file selected">
                             <span class="j-hint">Only: jpg / png , less 1Mb</span>
                         </div>
                     </div>
                     <!-- end file -->
                     <!-- start response from server -->
                     <div class="j-response"></div>
                     <!-- end response from server -->
                 </div>
                 <!-- end /.content -->
                 <div class="j-footer">
                     <button type="submit" class="btn btn-primary">Send</button>
                     <button type="reset" class="btn btn-default m-r-20">Reset</button>
                 </div>
                 <!-- end /.footer -->
             </form>
         </div>
     </div>
 </div>
