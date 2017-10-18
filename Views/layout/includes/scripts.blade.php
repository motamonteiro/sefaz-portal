<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{ config('sistema.cdn.js') }}scripts.min.js"></script>
<script src="https://use.fontawesome.com/76d36c1ad0.js"></script>
<script src="{{url('vendor/js/msg.js')}}"></script>
<script src="{{url('vendor/js/controller.js')}}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
@if (isset($erro))
    <script>
        swal({title: '', html: '{{ $erro }}', type: 'error', confirmButtonText: 'Ok'});
    </script>
@endif
@if (isset($sucesso))
    <script>
        swal({title: '', html: '{{ $sucesso }}', type: 'success', confirmButtonText: 'Ok'});
    </script>
@endif
@yield('scripts')
