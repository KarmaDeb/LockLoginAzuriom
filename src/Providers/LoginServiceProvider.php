<?php

namespace Azuriom\Plugin\LockLogin\Providers;

use Azuriom\Plugin\LockLogin\Events\LoginEvent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class LoginServiceProvider extends ServiceProvider {

    private array $search_hosts = [
        'https://karmaconfigs.ml/api/',
        'https://karmarepo.ml/api/',
        'https://karmadev.es/api/',
        'https://backup.karmaconfigs.ml/api/',
        'https://backup.karmarepo.ml/api/',
        'https://backup.karmadev.es/api/'
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
        $current_try_host = 0;

        if (Schema::hasColumn('users', 'player') && Schema::hasColumn('users', 'uuid')) {
            $users = DB::table('users')->get();
            $host = $this->search_hosts[$current_try_host];

            foreach ($users as $user) {
                DB::table('users')->where('name', '=', $user->name)->update(['player' => $user->name]);
                
                if (function_exists('curl_version')) {
                    if (!$this->isWorking($host)) {
                        if ($current_try_host <= count($this->search_hosts)) {
                            $current_try_host++;
                            $host = $this->search_hosts[$current_try_host];
                        } else {
                            break;
                        }
                    }
                    
                    $method = '?nick=' . $user->name;
                    if (empty($user->uuid) || $user->uuid == null) {
                        //We must fetch the client UUID

                        $acc_info = $this->get_web_page($host . $method);

                        $array = json_decode($acc_info, true);
                        if (isset($array['offline'])) {
                            $offlineData = $array['offline'];
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
                    echo "<script type='text/javascript'>alert('Curl is not enabled. It is highly recommended for the best LockLogin extension performance')</script>";
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