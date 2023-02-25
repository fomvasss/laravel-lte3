<script>
    @php
        $flashKeys = [
            'warning' => trans('lte3::alerts.warning'),
            'success' => trans('lte3::alerts.excellent'),
            'info' => trans('lte3::alerts.information'),
            'error' => trans('lte3::alerts.failure'),
        ];
    @endphp

    @foreach ($flashKeys as $key => $title)
        @if (Session::has($key))
            swal('{{ $title }}', '{{ Session::get($key) }}', '{{ $key }}');
        @endif
    @endforeach

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            swal('{{ trans('lte3::alerts.failure') }}', '{{ $error }}', 'error');
        @endforeach
    @endif
</script>