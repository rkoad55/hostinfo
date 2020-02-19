@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">{{ $batch->name }}</h3>
   

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($batch->domain) > 0 ? 'datatable' : '' }} ">
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
                      


                    </tr>
                </thead>
                
                <tbody>
                    @if (count($batch->domain) > 0)
                        @foreach ($batch->domain as $domain)
                            <tr data-entry-id="{{ $domain->id }}">
                               
                                <td><a href="{{ url("admin/domains") }}/{{ $domain->name }}">{{ $domain->name }}</a></td>

                                <?php
                                $history=$domain->history->last();

                                ?>
                                @if($history)
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

                                      @else
                                        <td> Not Resolved</td>
                                         <td> Not Resolved</td>
                                          <td> Not Resolved</td>
                                           <td> Not Resolved</td>
                                            <td> Not Resolved</td>
                                             <td> Not Resolved</td>
                                              <td> Not Resolved</td>
                                      @endif
                              

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


