<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain;
use App\History;
use App\Batch;
use DB;
use GeoIp2\Database\Reader;



class ResolveDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id,$domain,$batch_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Domain $domain, int $batch_id)
    {
        //
        $this->domain=$domain;
        $this->batch_id=$batch_id;
        // $this->user_id=auth()->user()->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

          
          $info=$this->getIP_DNS($this->domain->name);


          $history=History::create([
            'alexa' => $info['Alexa'],
            'dns' => $info['DNS'],
            'ip' => $info['IP'],
            'isp' => $info['ISP'],
            'org' => $info['Organization'],
            'mxIp' => $info['MX_IP'],
            'mxIsp' => $info['MX_ISP'],
            'mxOrg' => $info['MX_Organization'],
            'domain_id' => $this->domain->id
         
          ]);
          // $domain->attach($history->id);

          DB::table('batches')->whereId($this->batch_id)->increment('synced');
          // $batch=Batch::find()->first()->increment('synced');
          // $batch->save();
         
       


        
    }


        function alexa_rank($url){
    // return "999999999";
    $xmlURL = "http://data.alexa.com/data?cli=10&url=".$url;
    
    $ch = curl_init($xmlURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    
    $xml = simplexml_load_string($data); /*Also false*/

   
    if(isset($xml->SD)){
        if($xml->SD->REACH->attributes()=="0")
        {
            return "999999999";
        }
        else
        {

     
        return (string)$xml->SD->REACH->attributes();
        }
    }
    else{
        return "999999999";
    }

}

function getIP_DNS($domain)
{

  //DNS RESOLVE
                $reader = new Reader(app_path('GeoIP2-ISP.mmdb'));

                $domainInfo = [];
                //$domainInfo['dns']=[];

                //DNS_ALL is very slow DNS_ANY returns unreliable/incomplete records

                $dns_recordsA  = [];
                $dns_recordsNS = [];
                $dns_recordsMX = [];
                
                    try {
                        $dns_recordsA = @dns_get_record($domain, DNS_A);

                        if ($dns_recordsA == false) {
                            $dns_recordsA = [];

                                    try {
                                    $dns_recordsA = @dns_get_record('www.'.$domain, DNS_A);

                                    if ($dns_recordsA == false) {
                                        $dns_recordsA = [];
                                    }

                                } catch (Exception $e) {

                                    $dns_recordsA = [];

                                }
                        }

                    } catch (Exception $e) {

                        $dns_recordsA = [];

                    }

                    try {

                        $dns_recordsNS = @dns_get_record($domain, DNS_NS);

                        if ($dns_recordsNS == false) {
                            $dns_recordsNS = [];
                                         try {

                                            $dns_recordsNS = @dns_get_record("www.".$domain, DNS_NS);

                                            if ($dns_recordsNS == false) {
                                                $dns_recordsNS = [];
                                            }
                                        } catch (Exception $e) {

                                            $dns_recordsNS = [];

                                        }
                        }
                    } catch (Exception $e) {

                        $dns_recordsNS = [];

                    }

                    try {

                        $dns_recordsMX = @dns_get_record($domain, DNS_MX);

                        if ($dns_recordsMX == false) {
                            $dns_recordsMX = [];

                                    try {

                                        $dns_recordsMX = @dns_get_record("www.".$domain, DNS_MX);

                                        if ($dns_recordsMX == false) {
                                            $dns_recordsMX = [];
                                        }

                                    } catch (Exception $e) {

                                        $dns_recordsMX = [];
                                    }
                        }

                    } catch (Exception $e) {

                        $dns_recordsMX = [];
                    }

               
                // var_dump($dns_records);
                // die();
                $domainInfo['Alexa']  = '999999999';
                $domainInfo['Domain'] = $domain;
                $domainInfo['DNS']    = "";
                $domainInfo['IP']     = "";

                $domainInfo['ISP']          = "";
                $domainInfo['Organization'] = "";

                $domainInfo['MX_ISP']          = "";
                $domainInfo['MX_IP']           = "";
                $domainInfo['MX_Organization'] = "";

                foreach ($dns_recordsNS as $dns) {
                    $domainInfo['DNS'] .= "" . $dns['target'] . ",";
                }

                foreach ($dns_recordsA as $dns) {

                    $domainInfo['IP'] .= "" . $dns['ip'] . ",";

                }

                $domainInfo['IP']  = rtrim($domainInfo['IP'], "\r\n");
                $domainInfo['DNS'] = rtrim($domainInfo['DNS'], "\r\n");
                $domainInfo['IP']  = rtrim($domainInfo['IP'], ",");
                $domainInfo['DNS'] = rtrim($domainInfo['DNS'], ",");

// Replace "city" with the appropriate method for your database, e.g.,
                // "country".

                if (isset($dns_recordsA[0]['ip'])) {

                    // var_dump($dns_recordsA[0]['ip']);
                    // die();
                    if (filter_var($dns_recordsA[0]['ip'], FILTER_VALIDATE_IP)) {
                        try {
                            $record = $reader->isp($dns_recordsA[0]['ip']);
                        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
                            $record = false;
                        }

                        if (isset($record->isp)) {
                            $domainInfo['ISP'] = $record->isp;
                        }

                        if (isset($record->organization)) {
                            $domainInfo['Organization'] = $record->organization;
                        }

                    }
                }

                if (isset($dns_recordsMX[0]['target'])) {
                    $mxip = gethostbyname($dns_recordsMX[0]['target']);

                    if (filter_var($mxip, FILTER_VALIDATE_IP)) {

                        $domainInfo['MX_IP'] = $mxip;

                        try {
                            $record1 = $reader->isp($mxip);
                        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
                            $record1 = false;
                        }

                        if (isset($record1->isp)) {
                            $domainInfo['MX_ISP'] = $record1->isp;
                        }

                        if (isset($record1->organization)) {
                            $domainInfo['MX_Organization'] = $record1->organization;
                        }
                    }
                }
                // dd($domain);
               // $domainInfo['Alexa'] = $this->alexa_rank($domain);

                // dd($domainInfo);
                return $domainInfo;

                //DNS RESOLVE END
                //
}
}
