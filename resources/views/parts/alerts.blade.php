@if(in_array('bootstrap', config('lte3.view.alerts', [])))
@include('lte3::parts.alerts.bootstrap')
@endif

@if(in_array('toastr', config('lte3.view.alerts', [])))
@push('scripts')
@include('lte3::parts.alerts.toastr')
@endpush
@endif

@if(in_array('sweetalert', config('lte3.view.alerts', [])))
@push('scripts')
@include('lte3::parts.alerts.sweetalert')
@endpush
@endif
