<script src="{{ url('assets/jquery/jquery.min.js') }}"></script>
<script src="{{ url('assets/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ url('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('assets/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ url('assets/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ url('assets/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/toastr/toastr.min.js') }}"></script>
<script src="{{ url('dist/js/adminlte.js') }}"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
@stack('js')
@if (session('success'))
    <script>
        toastr.success('{{ session('success') }}')
    </script>
@endif
@if (session('error'))
    <script>
        toastr.error('{{ session('error') }}')
    </script>
@endif
@if (session('warning'))
    <script>
        toastr.warning('{{ session('warning') }}')
    </script>
@endif
@if (session('info'))
    <script>
        toastr.info('{{ session('info') }}')
    </script>
@endif
