<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Otserver\Website;
use Otserver\Error_Critic;
use Otserver\Database;

class DatabaseProvider extends ServiceProvider
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
        if (Website::getServerConfig()->isSetKey('mysql')) {
            $SERVERCONFIG_SQL_TYPE = 'sqlType';
            $SERVERCONFIG_SQL_TYPE = 'sqlType';
            $SERVERCONFIG_SQL_HOST = 'sqlHost';
            $SERVERCONFIG_SQL_PORT = 'sqlPort';
            $SERVERCONFIG_SQL_USER = 'sqlUser';
            $SERVERCONFIG_SQL_PASS = 'sqlPass';
            $SERVERCONFIG_SQL_DATABASE = 'sqlDatabase';
            $SERVERCONFIG_SQLITE_FILE = 'sqlFile';
        } elseif (Website::getServerConfig()->isSetKey('sqlHost')) {
            $SERVERCONFIG_SQL_TYPE = 'sqlType';
            $SERVERCONFIG_SQL_HOST = 'sqlHost';
            $SERVERCONFIG_SQL_PORT = 'sqlPort';
            $SERVERCONFIG_SQL_USER = 'sqlUser';
            $SERVERCONFIG_SQL_PASS = 'sqlPass';
            $SERVERCONFIG_SQL_DATABASE = 'sqlDatabase';
            $SERVERCONFIG_SQLITE_FILE = 'sqlFile';
        } else {
            new Error_Critic(
                '#E-3',
                'There is no key <b>sqlHost</b> or <b>mysqlHost</b> in server config'
            );
        }

        if (Website::getServerConfig()->getValue($SERVERCONFIG_SQL_TYPE) == 'mysql') {
            Website::setDatabaseDriver(Database::DB_MYSQL);

            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_HOST))
                Website::getDBHandle()->setDatabaseHost(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_HOST));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_HOST . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_PORT))
                Website::getDBHandle()->setDatabasePort(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_PORT));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_PORT . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_DATABASE))
                Website::getDBHandle()->setDatabaseName(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_DATABASE));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_DATABASE . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_USER))
                Website::getDBHandle()->setDatabaseUsername(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_USER));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_USER . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_PASS))
                Website::getDBHandle()->setDatabasePassword(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_PASS));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_PASS . '</b> in server config file.');
        } elseif (Website::getServerConfig()->getValue($SERVERCONFIG_SQL_TYPE) == 'sqlite') {
            Website::setDatabaseDriver(Database::DB_SQLITE);
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQLITE_FILE))
                Website::getDBHandle()->setDatabaseFile(Website::getServerConfig()->getValue($SERVERCONFIG_SQLITE_FILE));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQLITE_FILE . '</b> in server config file.');
        } elseif (Website::getServerConfig()->getValue($SERVERCONFIG_SQL_TYPE) == 'pgsql') {
            // does pgsql use 'port' parameter? I don't know
            Website::setDatabaseDriver(Database::DB_PGSQL);
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_HOST))
                Website::getDBHandle()->setDatabaseHost(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_HOST));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_HOST . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_DATABASE))
                Website::getDBHandle()->setDatabaseName(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_DATABASE));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_DATABASE . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_USER))
                Website::getDBHandle()->setDatabaseUsername(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_USER));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_USER . '</b> in server config file.');
            if (Website::getServerConfig()->isSetKey($SERVERCONFIG_SQL_PASS))
                Website::getDBHandle()->setDatabasePassword(Website::getServerConfig()->getValue($SERVERCONFIG_SQL_PASS));
            else
                new Error_Critic('#E-7', 'There is no key <b>' . $SERVERCONFIG_SQL_PASS . '</b> in server config file.');
        } else {
            new Error_Critic('#E-6', 'Database error. Unknown database type in <b>server config</b> . Must be equal to: "<b>mysql</b>", "<b>sqlite</b>" or "<b>pgsql</b>" . Now is: "<b>' . Website::getServerConfig()->getValue($SERVERCONFIG_SQL_TYPE) . '</b>"');
        }
        Website::setPasswordsEncryption(Website::getServerConfig()->getValue('encryptionType'));
        Website::getDBHandle()->setPrintQueries(true);
    }
}
