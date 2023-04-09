@extends('lte3::parts.filter-wrap')

@section('body')
{!! Lte3::hidden('type', 'projects') !!}
<div class="row">
    <div class="col-md-4">
        {!! Lte3::text('name', null, ['label' => 'Name']) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::select2('status', 'new', ['Success', 'Paused', 'Canceled', 'New', 'Old'], [
            'label' => 'Status',
            'multiple' => 1,
            'id' => 'status2'
        ]) !!}
    </div>
    <div class="col-md-4">
        {!! Lte3::datetimepicker('datetime', now(), [
            'label' => 'Datetime',
        ]) !!}
    </div>
</div>
@stop
