<?php

namespace App\Http\Controllers\Admin;

use App\Batch;
use App\Domain;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBatchesRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use App\Jobs\ResolveDomain;
use App\Jobs\ResolveBatch;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Silber\Bouncer\Database\Role;
// use Iodev\Whois\Whois;
class BatchController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

// $whois = Whois::create();
// $info = $whois->loadDomainInfo("wasiflaeeq.com");

//         dd($info);
//         dd($info->getval('email'));


// $whois = Whois::create();

//         // Define custom whois host
//         $customServer = new TldServer(".com", "whois.markmonitor.com", false, TldParser::create());

//         // // Or define the same via assoc way
//         // $customServer = TldServer::fromData([
//         //     "zone" => ".com",
//         //     "host" => "whois.nic.custom",
//         // ]);

//         // Add custom server to existing whois instance
//         $whois->getTldModule()->addServers([$customServer]);

//         // Now it can be utilized
//         $info = $whois->loadDomainInfo("google.com");

//         var_dump($info);


//         print_r([
//             'Domain created' => date("Y-m-d", $info->getCreationDate()),
//             'Domain expires' => date("Y-m-d", $info->getExpirationDate()),
//             'Domain owner' => $info->getOwner(),
//         ]);


//         $dns_recordsA = @dns_get_record("reliablehome.ca", DNS_ALL);

//         dd($dns_recordsA);

        $batches = Batch::whereRaw('synced>=total')->get();

        $incompleteBatches = Batch::whereRaw('synced<total')->get();
        // dd($incompleteBatches->count());

        // ResolveDomain::dispatch(Domain::findOrFail(217),2);
// dd($batches);
        return view('admin.batches.index', compact('batches','incompleteBatches'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // $chk=gethostbyname("crypto-forex.pw");

        // dd($chk);
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        return view('admin.batches.create', compact('roles'));
    }
	
	public function create2()
    {

        // $chk=gethostbyname("crypto-forex.pw");

        // dd($chk);
        
      //  $roles = Role::get()->pluck('name', 'name');
        
      return view('admin.batches.create2');
    }







    public function store2(StoreBatchesRequest $request)
    {
         
        $domain = $request->name;
        //$domains = $request['domains'];
       // $domain = "Computer-Masters.net";

    $ip = gethostbyname($domain);


  


  $curl = curl_init();
  $auth_data = array(
     
    
      
  );
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
  curl_setopt($curl, CURLOPT_URL, 'http://ip-api.com/php/'.$ip);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  $result = curl_exec($curl);
  if(!$result){die("Connection Failure");}
  curl_close($curl);
//   /return $result->country;
  // $result = json_encode($result , true);
    //  $result = json_decode($result , true);
 //return $result;
 /*
$data= str_replace(";",",",$result);
$ok = "[".$data."]";

 $result = json_decode($ok , true);

 return $result['country'];


/*
echo "Domain: ".$domain;
echo "<br>";
echo "IP: ".$ip;

*/
        // dd($domain);
          $original_array=unserialize($result); 
        // return  redirect()->route('admin/batches/create2', compact('original_array'));
       

        return view('admin/batches/create2', compact('original_array'));
    }




    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreBatchesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBatchesRequest $request)
    {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $request = $request->all();
        $domains = $request['domains'];
        $domainsFile=false;
        if(isset($_FILES['domainsFile']))
        {
        if($_FILES['domainsFile']['error']==0)
        {   
             // $source_file = fopen($_FILES['domainsFile']['tmp_name'], "r" ) or die("Couldn't open filename");

             $domainsFile=file_get_contents($_FILES['domainsFile']['tmp_name']); 
             // dd($domainsFile);

             $domains= $domainsFile;
        }
        }  
        // $domainsFile= $request->all()['domainsFile'];
        //

        if(isset($request['resync']))
        {
            $resync=1;
        }
        else
        {
            $resync=0;
        }
        // dd(\Auth::user()->id);
        $uniqueString = unique_random('batches', 'unique_id', 32);

        $batch = Batch::create([
            'name'      => $request['name'],
            'unique_id' => $uniqueString,
            'resync' => $resync,
            'user_id'   => \Auth::user()->id,
        ]);

        $domains = explode("\n", $domains);
        // dd($domains);
        // $domains=array_unique($domains);

        $existingDomains = Domain::all();


        $insertDomains=[];
        $allDomains=[];

        $n=0;

        foreach ($domains as $domain) {

            $domain = $this->get_domain($domain);
            // dd($domain);
            $domain = filter_var($domain, FILTER_SANITIZE_URL);

            if ($domain) {
                array_push($allDomains,$domain);
                // $existing = $existingDomains->where('name', $domain)->first();

                // if (!$existing) {
                    // if(!in_array($insertDomains, [
                    //     'name' => $domain,
                    // ]))
                    // {
                    array_push($insertDomains, [
                        'name' => $domain,
                    ]);
                // }
                    



                    $n++;

                    if($n==100)
                    {


                        Domain::insert($insertDomains);
                        $insertDomains=[];
                        $n=0;
                    }

                } 
                // else {
                //     $domain = $existing;
                //      // $domain->batch()->attach($batch->id);
                // }
                

               

            // }
        }

  // dd($insertDomains);
// $insertDomains=array_unique($insertDomains);
// $allDomains=array_unique($allDomains);
 Domain::insert($insertDomains);

// $allDomains=array_unique($allDomains);
// 

// $existingDomains = Domain::all();


$allDomains=Domain::whereIn("name",$allDomains)->get();
// ->pluck('id')->toArray()
$ids=array();
foreach ($allDomains as $exd) {
    # code...

   // dd($exd->batch->first());
    if($exd->batch->first()==null){

        array_push($ids, $exd->id);
    }
    else
    {
       
    }
}


// dd($ids);
// $allDomains=array_unique($allDomains);

$batch->domain()->attach($ids);
// dd($allDomains);

           

            // dd($batch->domain);
                // $domain->batch()->attach($batch->id);
 $batch->total=$batch->domain->count();
        $batch->save();


            ResolveBatch::dispatch($batch->id);
                // ResolveDomain::dispatch($domain, $batch->id);



        $batch->total=$batch->domain->count();
        $batch->save();
        // dd($domain);
        return redirect()->route('admin.batches.index');
    }

    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function get_domain($url)
    {


        $url=str_replace([" ","        ","   "], "", $url);
        if (strpos($url, "https://") === false and strpos($url, "http://") === false) {
            $url = "http://" . $url;
        }
        $url = str_replace(array("\n", "\r"), '', $url);
        // dd($url);
        $urlobj = parse_url($url);

        //$urlobj=str_replace("http://www.", "http://", $urlobj);
        $domain = $urlobj['host'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            //echo $regs['domain'];
            return $regs['domain'];
        }
        return false;
    }

    public function show($id)
    {

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $batch = Batch::where('unique_id',$id)->with('domain.history')->first();

        // dd($Batch->domain->first()->history->last());
       

        return view('admin.batches.show', compact('batch'));
    }

    public function showDomain($domain)
    {

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $domain = Domain::where('name',$domain)->with('history')->orderBy("id")->first();
        
        // dd($Batch->domain->first()->history->last());
       
        // dd($domain->history);
        return view('admin.batches.showDomain', compact('domain'));
    }


    public function ajax($id)
    {

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $batch = Batch::where('unique_id',$id)->first();

        // dd($Batch->domain->first()->history->last());
       
        return  response()->json([
                'total' => $batch->total,
                'synced' => $batch->synced,
                'bname' => $batch->name
            ]);
       // return view('admin.batches.show', compact('batch'));
    }

    public function resync($id, Request $request)
    {

        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $batch = Batch::where('unique_id',$id)->first();


        $batch->total=$batch->domain->count();

        

        if($batch->synced<$batch->total)
        {
             $request->session()->flash('message', "This Batch is already being synced");
             // ResolveBatch::dispatch($batch->id);
        }
        else
        {
            ResolveBatch::dispatch($batch->id);
     


            $batch->synced=0;

            $batch->save();    
        }
        
        // sleep(2);
        // dd($Batch->domain->first()->history->last());
       

         return redirect()->route('admin.batches.index');
    }
    public function edit($id)
    {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, $id)
    {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $user = User::findOrFail($id);
        $user->update($request->all());
        foreach ($user->roles as $role) {
            $user->retract($role);
        }
        foreach ($request->input('roles') as $role) {
            $user->assign($role);
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $batch = Batch::findOrFail($id);
        $batch->delete();

        return redirect()->route('admin.batches.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
