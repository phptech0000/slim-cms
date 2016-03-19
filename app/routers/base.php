<?php
use \Illuminate\Database\Capsule\Manager as DB;
use \Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Users;
$app->get('/test1', function(){
    echo "string";
})->setName('asdf');
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