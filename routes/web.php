<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/', Index\HomeController::class)->only([
    'index'
]);

Route::group(['prefix' => '/'], function () {
    # Account Management
    Route::prefix('/accountmanagement')->group(function () {
        Route::get('/', 'Index\AccountManagementController@index')->name('accountmanagement.index')->middleware('auth');

        Route::match(['get', 'post'], '/login', 'Index\AccountManagementController@login')->name('accountmanagement.login');
        Route::get('/logout', 'Index\AccountManagementController@logout')->name('accountmanagement.logout')->middleware('auth');

        Route::match(['get', 'post'], '/changepassword', 'Index\AccountManagementController@changePassword')->name('accountmanagement.changepassword')->middleware('auth');
        Route::match(['get', 'post'], '/changeemail', 'Index\AccountManagementController@changeemail')->name('accountmanagement.changeemail')->middleware('auth');
        Route::match(['get', 'post'], '/changeinfo', 'Index\AccountManagementController@changeinfo')->name('accountmanagement.changeinfo')->middleware('auth');
        Route::match(['get', 'post'], '/registeraccount', 'Index\AccountManagementController@registeraccount')->name('accountmanagement.registeraccount')->middleware('auth');
        Route::match(['get', 'post'], '/newreckey', 'Index\AccountManagementController@newreckey')->name('accountmanagement.newreckey')->middleware('auth');
        Route::match(['get', 'post'], '/createcharacter/{world?}', 'Index\AccountManagementController@createcharacter')->name('accountmanagement.createcharacter')->middleware('auth');
        Route::match(['get', 'post'], '/changecomment/{name}', 'Index\AccountManagementController@changecomment')->name('accountmanagement.changecomment')->middleware('auth');
        Route::match(['get', 'post'], '/newnick/{name}', 'Index\AccountManagementController@newnick')->name('accountmanagement.newnick')->middleware('auth');
        Route::match(['get', 'post'], '/deletecharacter/{name}', 'Index\AccountManagementController@deletecharacter')->name('accountmanagement.deletecharacter')->middleware('auth');
        Route::match(['get', 'post'], '/undelete/{name}', 'Index\AccountManagementController@undelete')->name('accountmanagement.undelete')->middleware('auth');
    });

    # Create Account
    Route::prefix('/createaccount')->group(function () {
        Route::match(['get', 'post'], '/', 'Index\CreateAccountController@index')->name('createaccount.index');
    });

    # Create Account
    Route::prefix('/lostaccount')->group(function () {
        Route::match(['get', 'post'], '/', 'Index\LostAccountController@index')->name('lostaccount.index');
        Route::match(['get', 'post'], '/step1', 'Index\LostAccountController@step1')->name('lostaccount.step1');
        Route::match(['get', 'post'], '/step2', 'Index\LostAccountController@step2')->name('lostaccount.step2');
        Route::match(['get', 'post'], '/step3', 'Index\LostAccountController@step3')->name('lostaccount.step3');
        Route::match(['get', 'post'], '/checkcode', 'Index\LostAccountController@checkcode')->name('lostaccount.checkcode');
        Route::match(['post'], '/sendcode', 'Index\LostAccountController@sendcode')->name('lostaccount.sendcode');
        Route::match(['post'], '/setnewpassword', 'Index\LostAccountController@setnewpassword')->name('lostaccount.setnewpassword');
    });

    # Highscores
    Route::get('/ranking/{list?}/{world?}/{vocation?}', 'Index\HighscoresController@index')->name('ranking.index')->where(['list' => '[a-z]+']);

    # Characters
    Route::get('/characters/{name?}', 'Index\CharactersController@index')->name('characters.index');
    Route::match(['get', 'post'], '/redirectWithParams/characters', ['as' => 'search', 'uses' => 'Index\CharactersController@redirectWithParams'])->name('characters.redirectWithParams');

    # Vocations
    Route::prefix('/vocations')->group(function () {
        Route::match(['get'], '/', 'Index\VocationsController@index')->name('vocations.index');
        Route::match(['get'], '/{vocation}', 'Index\VocationsController@show')->name('vocations.show');
    });

    # KillStatistics
    Route::get('/killstatistics', 'Index\KillStatisticsController@index')->name('killstatistics.index');

    # Houses
    Route::match(['get', 'post'], '/houses/{world?}/{town?}/{owner?}/{order?}', 'Index\HousesController@index')->name('houses.index')->where('world', '[0-9]+');

    # Spells
    Route::match(['get', 'post'], '/spells', 'Index\SpellsController@index')->name('spells.index');

    # Guilds
    Route::prefix('/guilds')->group(function () {
        Route::get('/', 'Index\GuildsController@index')->name('guilds.index');

        Route::match(['get', 'post'], '/show/{guildId}', 'Index\GuildsController@show')->name('guilds.show')->middleware('auth');
        Route::match(['get', 'post'], '/changerank/{guildId}', 'Index\GuildsController@changerank')->name('guilds.changerank')->middleware('auth');
        Route::match(['get', 'post'], '/deleteinvite/{guildId}/{name}', 'Index\GuildsController@deleteinvite')->name('guilds.deleteinvite')->middleware('auth');
        Route::match(['get', 'post'], '/invite/{guildId}', 'Index\GuildsController@invite')->name('guilds.invite')->middleware('auth');
        Route::match(['get', 'post'], '/acceptinvite/{guildId}', 'Index\GuildsController@acceptinvite')->name('guilds.acceptinvite')->middleware('auth');
        Route::match(['get', 'post'], '/kickplayer/{guildId}/{name}', 'Index\GuildsController@kickplayer')->name('guilds.kickplayer')->middleware('auth');
        Route::match(['get', 'post'], '/leaveguild/{guildId}', 'Index\GuildsController@leaveguild')->name('guilds.leaveguild')->middleware('auth');
        Route::match(['get', 'post'], '/createguild', 'Index\GuildsController@createguild')->name('guilds.createguild')->middleware('auth');
        Route::match(['get', 'post'], '/manager/{guildId}', 'Index\GuildsController@manager')->name('guilds.manager')->middleware('auth');
        Route::match(['get', 'post'], '/changelogo/{guildId}', 'Index\GuildsController@changelogo')->name('guilds.changelogo')->middleware('auth');
        Route::match(['get', 'post'], '/deleterank/{guildId}/{rankId}', 'Index\GuildsController@deleterank')->name('guilds.deleterank')->middleware('auth');
        Route::match(['get', 'post'], '/addrank/{guildId}', 'Index\GuildsController@addrank')->name('guilds.addrank')->middleware('auth');
        Route::match(['get', 'post'], '/changedescription/{guildId}', 'Index\GuildsController@changedescription')->name('guilds.changedescription')->middleware('auth');
        Route::match(['get', 'post'], '/passleadership/{guildId}', 'Index\GuildsController@passleadership')->name('guilds.passleadership')->middleware('auth');
        Route::match(['get', 'post'], '/deleteguild/{guildId}', 'Index\GuildsController@deleteguild')->name('guilds.deleteguild')->middleware('auth');
        Route::match(['get', 'post'], '/deletebyadmin/{guildId}', 'Index\GuildsController@deletebyadmin')->name('guilds.deletebyadmin')->middleware('auth');
        Route::match(['get', 'post'], '/changemotd/{guildId}', 'Index\GuildsController@changemotd')->name('guilds.changemotd')->middleware('auth');
        Route::match(['get', 'post'], '/saveranks/{guildId}', 'Index\GuildsController@saveranks')->name('guilds.saveranks')->middleware('auth');
        Route::match(['get', 'post'], '/cleanupplayers', 'Index\GuildsController@cleanupplayers')->name('guilds.cleanupplayers')->middleware('auth');
        Route::match(['post'], '/changenick/{guildId}/{name}', 'Index\GuildsController@changenick')->name('guilds.changenick')->middleware('auth');
    });

    # Shop System
    Route::prefix('/shop')->group(function () {
        Route::get('/{id?}', 'Index\ShopController@index')->name('shop.index');
        Route::match(['get'], '/selectplayer/{buyId}', 'Index\ShopController@selectplayer')->name('shop.selectplayer');
        Route::match(['post'], '/confirmtransaction', 'Index\ShopController@confirmtransaction')->name('shop.confirmtransaction');
    });

    # Web Shop
    Route::prefix('/webshop')->group(function () {
        Route::match(['get', 'post'], '/', 'Index\WebShopController@index')->name('webshop.index');
        Route::match(['get', 'post'], '/finish', 'Index\WebShopController@finish')->name('webshop.finish');
        Route::match(['get', 'post'], '/create-checkout-session', 'Index\WebShopController@checkout')->name('stripe.checkout');
    });

    # Whoisonline
    Route::match(['get', 'post'], '/whoisonline/{world?}/{order?}/{orderDirection?}/{orderAlphabetic?}', 'Index\WhoisOnlineController@index')->name('whoisonline.index');

    # Experience Table
    Route::match(['get', 'post'], '/experiencetable', 'Index\ExperienceTableController@index')->name('experiencetable.index');

    # Regras
    Route::get('/rules', 'Index\RulesController@index')->name('rule.index');

    # Privacy Policy
    Route::get('/privacy', 'Index\PrivacyPolicyController@index')->name('privacy.index');
});

Route::group(['prefix' => '/stripe'], function () {
    Route::match(['get', 'post'], '/stripe-key', 'Index\StripeController@stripeKey')->name('stripe.stripeKey');
    Route::match(['get', 'post'], '/pay', 'Index\StripeController@pay')->name('stripe.pay');
});

Route::get('/checkname/{name}', 'Index\AccountManagementController@checkname')->name('accountmanagement.checkname');
Route::get('/checkaccountname/{name}', 'Index\CreateAccountController@checkaccountname')->name('createaccount.checkaccountname');
Route::get('/checkaccountemail/{email}', 'Index\CreateAccountController@checkaccountemail')->name('createaccount.checkaccountemail');
