@extends('layouts.app')

@section('content')
    <h3 class="page-title">Resolution Batch</h3>
    {!! Form::open(['method' => 'POST', 'files' => true, 'route' => ['admin.batches.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            Add Reosultion Batch
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Batch Name*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
             <div style="" class="row">
                <div class="col-xs-12 form-group">
                 <label for="type" class="control-label">Auto ReSync overnight</label>
                 <div>
                     <input autocomplete="off" name="resync" id="resync" type="checkbox" data-onstyle="success" data-offstyle="info" { data-toggle="toggle" data-off="Disable ReSync" data-on="Auto ReSync">
                     </div>
                </div>

                </div>


            <div class="row">
                <div class="col-xs-12 form-group">
                    <label class="control-label" for="domains">Domains</label>
                    <textarea class="form-control" id="domains" name="domains">{{ old('domains') }}</textarea>

                    <p class="help-block"></p>
                    @if($errors->has('domains'))
                        <p class="help-block">
                            {{ $errors->first('domains') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                     <label class="control-label" for="domainsFile">Domains File</label>
                <input type="file" name="domainsFile" id="domainsFile">
                    <p class="help-block"></p>
                    @if($errors->has('domainsFile'))
                        <p class="help-block">
                            {{ $errors->first('domainsFile') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

