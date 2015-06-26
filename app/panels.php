<?php namespace GeoRedirect;

/** @var \Herbert\Framework\Panel $panel  */

$panel->add(array(
    'type'   => 'panel',
    'as'     => 'mainPanel',
    'title'  => 'GeoRedirect',
    'slug'   => 'geo-redirect',
    'uses'   => __NAMESPACE__ . '\Controllers\MainController@index'
));

/*
Next version

$panel->add(array(
    'type'   => 'sub-panel',
    'parent' => 'mainPanel',
    'as'     => 'subPanel',
    'title'  => 'Settings',
    'slug'   => 'geo-redirect-settings',
    'uses'   => __NAMESPACE__ . '\Controllers\SettingsController@index'

));
*/