@extends('layouts.app')

@section('content')
    <h3 class="page-title">Doamin Name Search</h3>
    {!! Form::open(['method' => 'POST', 'files' => true, 'url' => 'admin/batches/store2']) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Domain Url(example.com)*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
             


           
           
            
        </div>
    </div>

    {!! Form::submit('Search', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}






    <div class="panel-body table-responsive">

@if(isset($original_array))
    @if($original_array['status']=="success")
    <table class="table table-sm table-dark">
  <thead>
    <tr>
      
      <th scope="col"></th>
      <th scope="col"></th>
     
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Status</th>
      
      <td>{{ $original_array['status'] }}</td>
    </tr>
    <tr>
      <th scope="row">IP Address</th>
      
      <td>{{ $original_array['query'] }}</td>
    </tr>
    <tr>
      <th scope="row">Country</th>
     
      <td>{{ $original_array['country'] }}</td>
    </tr>
    <tr>
      <th scope="row">Country Code</th>
     
      <td>{{ $original_array['countryCode'] }}</td>
    </tr>
    <tr>
      <th scope="row">Region</th>
     
      <td>{{ $original_array['region'] }}</td>
    </tr>
    <tr>
      <th scope="row">Region Name</th>
     
      <td>{{ $original_array['regionName'] }}</td>
    </tr>
    <tr>
      <th scope="row">City</th>
     
      <td>{{ $original_array['city'] }}</td>
    </tr>
    <tr>
      <th scope="row">Zip</th>
     
      <td>{{ $original_array['zip'] }}</td>
    </tr>
    <tr>
      <th scope="row">Lat</th>
     
      <td>{{ $original_array['lat'] }}</td>
    </tr>
    <tr>
      <th scope="row">Lon</th>
     
      <td>{{ $original_array['lon'] }}</td>
    </tr>
    <tr>
      <th scope="row">Time Zone</th>
     
      <td>{{ $original_array['timezone'] }}</td>
    </tr>
    <tr>
      <th scope="row">ISP</th>
     
      <td>{{ $original_array['isp'] }}</td>
    </tr>
    <tr>
      <th scope="row">ORG</th>
     
      <td>{{ $original_array['org'] }}</td>
    </tr>
    <tr>
      <th scope="row">AS</th>
     
      <td>{{ $original_array['as'] }}</td>
    </tr>
    
   
    
  </tbody>
</table>
@else 

<h1>Url Not Foud</h1>


@endif


        </div>

        @endif








@stop

