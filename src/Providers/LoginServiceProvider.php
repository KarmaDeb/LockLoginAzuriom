<?php

namespace Azuriom\Plugin\LockLogin\Providers;

use Azuriom\Plugin\LockLogin\Events\LoginEvent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class LoginServiceProvider extends ServiceProvider {

    private array $search_hosts = [
        'https://karmaconfigs.ml/',
        'https://karmarepo.ml/',
        'https://karmadev.es/',
        'https://backup.karmaconfigs.ml/',
        'https://backup.karmarepo.ml/',
        'https://backup.karmadev.es/'
    ];

	/**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Login::class => [
            LoginEvent::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        if (function_exists('curl_version')) {
            $current_try_host = 0;

            $host = $this->search_hosts(0);

            if (!$this->isWorking($host)) {
                while (!$this->isWorking($host) && $current_try_host < count($this->search_hosts)) {
                    $current_try_host++;
                    $host = $this->search_hosts($current_try_host);
                }
            }

            if ($this->isWorking($host)) {
                $acc_info = $this->get_web_page($host . 'api/?fetch=@all');
                $array = json_decode($acc_info, true);
                
                if (gettype($array) == 'array') {
                    $legacy = true;
                    if (isset($array['success'])) {
                        //New API. Expected to be used before 2023
                        $acc_info = $this->get_web_page($host . 'api/?method=minecraft&action=fetch&query=*');
                        $array = json_decode($acc_info);

                        $legacy = false;
                    }

                    if (Schema::hasColumn('users', 'player') && Schema::hasColumn('users', 'uuid')) {
                        $users = DB::table('users')->get();

                        foreach ($users as $user) {
                            DB::table('users')->where('name', '=', $user->name)->update(['player' => $user->name]);
                            
                            if (empty($user->uuid) || $user->uuid == null) {
                                if (isset($array[$user->name])) {
                                    $userInfo = $array[$user->name];

                                    if (isset($userInfo['offline'])) {
                                        $offlineData = $userInfo['offline'];
                                        if ($legacy) {
                                            if (isset($offlineData[0]['data'])) {
                                                $data = $offlineData[0]['data'];

                                                if (isset($data['id'])) {
                                                    $id = $data['id'];

                                                    if (!empty($id)) {
                                                        DB::table('users')->where('name', '=', $user->name)->update(['uuid' => $id]);
                                                    }
                                                }
                                            }
                                        } else {
                                            if (isset($offlineData['uuid'])) {
                                                $id = $offlineData['uuid'];

                                                if (!empty($id)) {
                                                    DB::table('users')->where('name', =, $user->name)->update(['uuid' => $id]);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($legacy) {
                                        $create_request = $this->get_web_page($host . 'api/?nick=' . $user->name);

                                        $userInfo = json_decode($create_request, true);
                                        if (gettype($userInfo) == 'array') {
                                            if (isset($userInfo['offline'])) {
                                                $offlineData = $userInfo['offline'];
                                                if (isset($offlineData[0]['data'])) {
                                                    $data = $offlineData[0]['data'];
    
                                                    if (isset($data['id'])) {
                                                        $id = $data['id'];
    
                                                        if (!empty($id)) {
                                                            DB::table('users')->where('name', '=', $user->name)->update(['uuid' => $id]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        $create_request = $this->get_web_page($host . 'api/?method=minecraft&action=create&query=' . $user->name);
                                    
                                        $jsonData = json_decode($create_request, true);
                                        if (gettype($jsonData) == 'array') {
                                            if (isset($create_request['success']) && $create_request['success']) {
                                                $accData = $create_request[$user->name];
                                                $offlineData = $accData['offline'];

                                                if (isset($offlineData['uuid'])) {
                                                    $id = $offlineData['uuid'];

                                                    if (!empty($id)) {
                                                        DB::table('users')->where('name', =, $user->name)->update(['uuid' => $id]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function isWorking($url) {
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return $httpCode == 200;
    }

    public function get_web_page($url) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
        ); 

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $content  = curl_exec($ch);

        curl_close($ch);

        return $content;
    }
}