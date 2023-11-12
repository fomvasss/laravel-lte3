@extends('lte3::parts.filter-wrap')

@section('body')
{!! Lte3::hidden('type', 'projects') !!} {{-- example hidden field --}}
<div class="row">
    <div class="col-md-3">
        {!! Lte3::text('name', null, ['label' => 'Name', 'default' => request('name')]) !!}
    </div>
    <div class="col-md-3">
        {!! Lte3::select2('status', 'new', ['Success', 'Paused', 'Canceled', 'New', 'Old'], [
            'label' => 'Status',
            'multiple' => 1,
            'id' => 'status2'
        ]) !!}
    </div>
    <div class="col-md-3">
        {!! Lte3::datetimepicker('datetime_from', now(), [
            'label' => 'Datetime from',
        ]) !!}
    </div>
    <div class="col-md-3">
        {!! Lte3::datetimepicker('datetime_to', now()->addDay(), [
            'label' => 'Datetime to',
        ]) !!}
    </div>
</div>
@stop
