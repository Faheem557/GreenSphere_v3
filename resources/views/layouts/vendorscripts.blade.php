<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- JQUERY JS -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

@if(file_exists(public_path('assets/plugins/p-scroll/perfect-scrollbar.js')))
    <!-- Perfect SCROLLBAR JS -->
    <script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize perfect scrollbar only if elements exist
        const scrollElements = document.querySelectorAll('.scroll-container');
        if (scrollElements.length > 0) {
            scrollElements.forEach(element => {
                new PerfectScrollbar(element);
            });
        }
    });
    </script>
@endif

<!-- SIDE-MENU JS -->
<script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- SIDEBAR JS -->
<script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>

@if(file_exists(public_path('assets/js/themeColors.js')))
    <!-- Color Theme js -->
    <script src="{{ asset('assets/js/themeColors.js') }}"></script>
@endif

<!-- Sticky js -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{ URL::asset('assets/js/circle-progress.min.js') }}"></script>

<!-- PIETY CHART JS-->
<script src="{{ URL::asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/peitychart/peitychart.init.js') }}"></script>

<!-- SPARKLINE JS-->
<script src="{{ URL::asset('assets/js/jquery.sparkline.min.js') }}"></script>

<!-- INTERNAL CHARTJS CHART JS-->
<script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>

<!-- INTERNAL SELECT2 JS -->
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>

<!-- DATA TABLE JS-->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!-- INTERNAL APEXCHART JS -->
<script src="{{ URL::asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/apexchart/irregular-data-series.js') }}"></script>

<!-- INTERNAL Flot JS -->
<script src="{{ URL::asset('assets/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/flot/jquery.flot.fillbetween.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/flot/chart.flot.sampledata.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/flot/dashboard.sampledata.js') }}"></script>

<!-- INTERNAL Vector js -->
<script src="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

<!-- TypeHead js -->
<script src="{{ URL::asset('assets/plugins/bootstrap5-typehead/autocomplete.js') }}"></script>
<script src="{{ URL::asset('assets/js/typehead.js') }}"></script>

<!-- INTERNAL INDEX JS -->
<!--<script src="{{ URL::asset('assets/js/index1.js') }}"></script>-->

<!-- CUSTOM JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>

<!-- Custom-switcher -->
<script src="{{ asset('assets/js/custom-swicher.js') }}"></script>

<!-- Switcher js -->
<script src="{{ asset('assets/switcher/js/switcher.js') }}"></script>

@yield('spasific-scripts')
@yield('page-wise-scripts')