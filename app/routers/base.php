<?php
use \Illuminate\Database\Capsule\Manager as DB;
use \Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\EventDispatcher\Event;

use App\Models\Users;
$app->get('/test1', function(){
    if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done']))
{
    Hybrid_Endpoint::process();
}try {
        $hybridauth = new Hybrid_Auth( APP_PATH.'config/socialAuth.php' );
        $adapter = $hybridauth->authenticate( "Vkontakte" );
    $user_profile = $adapter->getUserProfile();
    p($user_profile);
    } catch( Exception $e ){
        echo "Ooophs, we got an error: " . $e->getMessage();
    }
})->setName('asdf');

$app->get('/d', function($t){
    $this->dispatcher->dispatch('acme.action');
});

$app->options('/ajax', 'App\Controllers\Admin\UniversalAjaxController:update')->add('App\Middleware\CheckAjaxMiddleware')->setName('asdf1');
/*
$app->get('/install/user_views_settings', function($req, $res, $args){
    Capsule::schema()->dropIfExists('user_views_settings');
    Capsule::schema()->create('user_views_settings', function($table) {
        $table->increments('id');
        $table->integer('user_id');
        $table->string('group');
        $table->string('value');
        $table->string('option_type');
        $table->string('code');
    });
});
/*
$app->get('/install/users', function($req, $res, $args){
    //DB::table('users')->get();
    Capsule::schema()->dropIfExists('users');
    Capsule::schema()->create('users', function($table) {
        $table->increments('id');
        $table->string('email');
        $table->string('login');
        $table->string('password');
        $table->integer('active');
        $table->timestamps();
    });
});

$app->get('/install/groups', function($req, $res, $args){
    Capsule::schema()->dropIfExists('groups');
    Capsule::schema()->create('groups', function($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('description', 500);
        $table->integer('active');
        $table->timestamps();
    });
});

$app->get('/install/options', function($req, $res, $args){
    Capsule::schema()->dropIfExists('options');
    Capsule::schema()->create('options', function($table) {
        $table->increments('id');
        $table->integer('options_group_id');
        $table->string('name');
        $table->string('description', 500);
        $table->string('value');
        $table->string('type');
        $table->integer('code');
        $table->timestamps();
    });
});

$app->get('/install/options_group', function($req, $res, $args){
    Capsule::schema()->dropIfExists('options_group');
    Capsule::schema()->create('options_group', function($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('description', 500);
        $table->integer('active');
        $table->timestamps();
    });
});

$app->get('/install/pages', function($req, $res, $args){
    Capsule::schema()->dropIfExists('pages');
    Capsule::schema()->create('pages', function($table) {
        $table->increments('id');
        $table->string('name');
        $table->string('code');
        $table->string('url_prefix');
        $table->integer('category_id');
        $table->text('preview_text');
        $table->text('detail_text');
        $table->string('preview_picture');
        $table->string('detail_picture');
        $table->integer('show_in_menu');
        $table->integer('name_for_menu');
        $table->integer('active');
        $table->timestamps();
    });
});*/