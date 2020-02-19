@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">{{ $domain->name }}</h3>
   

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ $domain->history->count() > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>

                        <th>Domain</th>
                        <th>Nameservers</th>
                        <th>IP Address</th>
                        <th>ISP</th>
                        <th>Organization</th>
                        <th>MX IP</th>
                        <th>MX ISP</th>
                        <th>MX Organization</th>
                        <th>Fetched At</th>
                      


                    </tr>
                </thead>
                
                <tbody>
                    @if ($domain->history->count() > 0)
                    <?php
                       $domain->history= $domain->history->sortByDesc("id");
                    ?>
                        @foreach ($domain->history as $history)
                            <tr data-entry-id="{{ $history->id }}">
                               
                                <td>{{ $domain->name }}</td>
                                <td><?php foreach(explode(",",$history->dns) as $dns){
                                    echo $dns."<br>";
                                } ?></td>
                                <td><?php foreach(explode(",",$history->ip) as $ip){
                                    echo $ip."<br>";
                                } ?></td>
                                  <td>{{ $history->isp }}</td>
                                   <td>{{ $history->org }}</td>
                                    <td>{{ $history->mxIp }}</td>
                                     <td>{{ $history->mxIsp }}</td>
                                      <td>{{ $history->mxOrg }}</td>

                                       <td>{{ $history->created_at }}</td>
                              

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


