<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('georedirect_rules')) {
    Capsule::schema()->create('georedirect_rules', function($table) {
        $table->increments('id');
        $table->string('country', 20);
        $table->string('target', 20)->nullable();
        $table->string('target_type', 10)->nullable();
        $table->string('redirect_url', 200);
        $table->boolean('force_redirect');
    });
}