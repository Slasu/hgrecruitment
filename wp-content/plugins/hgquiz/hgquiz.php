<?php

/**
 *
 * @link              TBA
 * @since             1.0.0
 *
 * Plugin Name: HgQuiz
 * Plugin URI: TBA
 * Description: Quiz plugin for recruitment purposes
 * Version: 1.0.0
 * Author: Slawek Sulkowski
 * Author URI: TBA
 * License: GPL2
 * Text Domain: hgquiz
 */

if( !defined( 'ABSPATH' ) ) {
    die();
}

require 'vendor/autoload.php';

use HgQuiz\Main;

register_activation_hook( __FILE__, [ 'HgQuiz\Main', 'HgInstall' ] );

$HgQuiz = Main::init();