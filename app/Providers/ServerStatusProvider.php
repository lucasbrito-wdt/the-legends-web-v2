<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Otserver\ServerStatus;

class ServerStatusProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $statustimeout = 1;
        foreach (explode("*", str_replace(" ", "", 100)) as $status_var) {
            if ($status_var > 0) {
                $statustimeout = $statustimeout * $status_var;
            }

            $statustimeout = $statustimeout / 1000;

            config('otserver.status', @parse_ini_file('../cache/serverstatus.txt'));

            if (config('otserver.status.serverStatus_lastCheck') + $statustimeout < time()) {
                config('otserver.status.serverStatus_checkInterval', $statustimeout + 3);
                config('otserver.status.serverStatus_lastCheck', time());

                $statusInfo = new ServerStatus(config('otserver.site.serverIP'), 7171, 1);
                if ($statusInfo->isOnline()) {
                    config('otserver.status.serverStatus_online', 1);
                    config('otserver.status.serverStatus_players', $statusInfo->getPlayersCount());
                    config('otserver.status.serverStatus_playersMax', $statusInfo->getPlayersMaxCount());

                    $h = floor($statusInfo->getUptime() / 3600);
                    $m = floor(($statusInfo->getUptime() - $h * 3600) / 60);

                    config('otserver.status.serverStatus_uptime', $h . 'h ' . $m . 'm');
                    config('otserver.status.serverStatus_monsters', $statusInfo->getMonsters());
                } else {
                    config('otserver.status.serverStatus_online', 0);
                    config('otserver.status.serverStatus_players', 0);
                    config('otserver.status.serverStatus_playersMax', 0);
                }
                @$file = fopen("../cache/serverstatus.txt", "w");
                $file_data = '';
                foreach (config('otserver.status') as $param => $data) {
                    $file_data .= $param . ' = "' . str_replace('"', '', $data) . '"';
                }
                @rewind($file);
                @fwrite($file, $file_data);
                @fclose($file);
            }
        }
    }
}
