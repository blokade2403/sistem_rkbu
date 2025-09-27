 <!-- JAVASCRIPT -->
 <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

 <script src="{{ asset('libraries\assets\pages\data-table\extensions\row-reorder\js\dataTables.rowReorder.min.js') }}">
 </script>
 <script src="{{ asset('libraries\bower_components\datatables.net\js\jquery.dataTables.min.js') }}"></script>
 <script src="{{ asset('libraries\bower_components\datatables.net-buttons\js\dataTables.buttons.min.js') }}"></script>
 <script src="{{ asset('libraries\assets\pages\data-table\js\jszip.min.js') }}"></script>
 <script src="{{ asset('libraries\assets\pages\data-table\js\pdfmake.min.js') }}"></script>
 <script src="{{ asset('libraries\assets\pages\data-table\js\vfs_fonts.js') }}"></script>
 <script src="{{ asset('libraries\assets\pages\data-table\extensions\responsive\js\dataTables.responsive.min.js') }}">
 </script>
 <script src="{{ asset('libraries\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js') }}"></script>
 <script src="{{ asset('libraries\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js') }}">
 </script>
 <script src="{{ asset('libraries\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js') }}">
 </script>
 <script type="text/javascript" src="{{ asset('libraries\bower_components\jquery-i18next\js\jquery-i18next.min.js') }}">
 </script>
 <!-- Custom js -->
 <script src="{{ asset('libraries\assets\pages\data-table\extensions\row-reorder\js\row-reorder-custom.js') }}">
 </script>
 <!-- rating js -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>
 <!-- rating js -->
 {{-- <script type="text/javascript"
     src="{{ asset('libraries\bower_components\jquery-bar-rating\js\jquery.barrating.js') }}"></script> --}}

 <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
 <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
 <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
 <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
 <script src="{{ asset('assets/js/plugins.js') }}"></script>

 <!-- apexcharts -->
 <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

 <!-- Vector map-->
 <script src="{{ asset('assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
 <script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <!--Swiper slider js-->
 <script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

 <!-- Dashboard init -->
 <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

 <!-- App js -->
 <script src="{{ asset('assets/js/app.js') }}"></script>


 <!-- Choices.js JS -->
 <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

 <script>
     document.addEventListener("DOMContentLoaded", function() {
         const elements = document.querySelectorAll("[data-choices]");
         elements.forEach(function(el) {
             new Choices(el, {
                 searchEnabled: true, // aktifkan fitur search
                 itemSelectText: '', // hilangkan teks select
                 shouldSort: false // biar urutannya sesuai option awal
             });
         });
     });
 </script>

 @push('scripts')
     <script>
         $(document).ready(function() {
             $('#example-movie').barrating({
                 theme: 'bars-movie',
                 showSelectedRating: true // tampilkan nilai yang dipilih
             });
         });
     </script>
 @endpush
