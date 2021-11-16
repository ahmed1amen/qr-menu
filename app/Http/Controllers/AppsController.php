<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Akaunting\Module\Facade as Module;
use Illuminate\Support\Facades\File;
use ZipArchive;

class AppsController extends Controller
{
    public function index(){
        //1. Get all available apps
        $appsLink="https://raw.githubusercontent.com/mobidonia/foodtigerapps/main/apps30.json";
        $response = (new \GuzzleHttp\Client())->get($appsLink);

        $rawApps=[];
        if ($response->getStatusCode() == 200) {
            $rawApps = json_decode($response->getBody());
        }

        //2. Merge info
        foreach ($rawApps as $key => &$app) {
            $app->installed=Module::has($app->alias);
            if($app->installed){
                $app->version=Module::get($app->alias)->get('version');
                if($app->version==""){
                    $app->version="1.0";
                }

                //Check if app needs update
                if($app->latestVersion){
                    $app->updateAvailable=$app->latestVersion!=$app->version."";
                }else{
                    $app->updateAvailable=false;
                }
                
            }
            if(!isset($app->category)){
                $app->category=['tools'];
            }
        }

        //Filter apps by type
        $apps=[];
        $newRawApps=unserialize(serialize($rawApps));;
        foreach ($newRawApps as $key => $app) {
            if(isset($app->rule)&&$app->rule){
                if(config('app.'.$app->rule)){
                    array_push($apps,$app);
                }
            }else{
               array_push($apps,$app);
            }
        }
        

        //3. Return view
        return view('apps.index',compact('apps'));

    }

    public function remove($alias){
        if (strlen($alias)<2||(config('settings.is_demo') | config('settings.is_demo'))) {
            abort(404);
        }
 
        $curr=getcwd();
        $curr=str_replace('public','modules',$curr);

        //Modules folder - for plugins
        $destination=$curr."/".$alias;
       // dd($destination);
        if(File::exists($destination)){
            dd('exist');
        }else{
            dd('doesn exists');
            //File::deleteDirectory($destination);
            //return redirect()->route('apps.index')->withStatus(__('Removed'));
        }
        //dd(File::exists($destination));
       // dd($destination);
       
    }

    public function store(Request $request){
       
        $path=$request->appupload->storeAs('appupload', $request->appupload->getClientOriginalName());
        
        $fullPath = storage_path('app/'.$path);
        $zip = new ZipArchive;

        if ($zip->open($fullPath)) {

            //Modules folder - for plugins
            $destination=public_path('../modules');
            $message=__('App is installed');

            //If it is language pack
            if (strpos($fullPath, '_lang') !== false) {
                $destination=public_path('../resources/lang');
                $message=__('Language pack is installed');
            }


            // Extract file
            $zip->extractTo($destination);
            
            // Close ZipArchive     
            $zip->close();
            return redirect()->route('apps.index')->withStatus($message);
        }else{
            return redirect(route('apps.index'))->withError(__('There was an error on app install. Please try manual install'));
        }
    }
}
