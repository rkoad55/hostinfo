@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Resolution Batches</h3>
    <p>
        <a href="{{ route('admin.batches.create') }}" class="btn btn-success">Add New Batch</a>
    </p>

@if( count($incompleteBatches) > 0)
@foreach($incompleteBatches as $batch)
{{-- <div style="height: 40px;" batchId="{{  $batch->unique_id }}" id="prog_{{  $batch->unique_id }}" class="progress resolving">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{ $batch->synced }}" aria-valuemin="2" aria-valuemax="{{ $batch->total }}" style="width: {{ ($batch->synced/$batch->total) *100 }}%">{{ $batch->synced."/".$batch->total  }}</div>
</div> --}}

@endforeach
@endif
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>



        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($batches) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>

                        <th>Batch Name</th>
                        <th>Auto Resync</th>
                        <th>Total Domains</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($batches) > 0)
                        @foreach ($batches as $user)
                            <tr data-entry-id="{{ $user->id }}">
                                <td></td>

                                <td><a href="batches/{{ $user->unique_id }}">{{ $user->name }}</a></td>
                                <td>{{ $user->resync?"Enabled":"Disabled" }}</td>
                                <td>
                                    {{ $user->total }} Domains
                                </td>
                                <td>

                                    <a href="{{ route('admin.batches.resync',[$user->unique_id]) }}" class="btn btn-xs btn-info">Resync</a>
                                    <a style="display: none;" href="{{ route('admin.batches.edit',[$user->unique_id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                   {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.batches.destroy', $user->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop


