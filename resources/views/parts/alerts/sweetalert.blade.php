<script>
    @php
        $flashKeys = [
            'warning' => 'Warting!',
            'success' => 'Excelent!',
            'info' => 'Information!',
            'error' => 'Failure!',
        ];
    @endphp

    @foreach ($flashKeys as $key => $title)
        @if (Session::has($key))
            Swal.fire('{{ $title }}', '{{ Session::get($key) }}', '{{ $key }}');
        @endif
    @endforeach

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            Swal.fire('Failure!', '{{ $error }}', 'error');
        @endforeach
    @endif
</script>