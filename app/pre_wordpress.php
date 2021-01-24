<?php

require_once(dirname(__FILE__) . '/wp-content/mu-plugins/insta-wp/src/Modules/FastHttpHandler/FastHttpHandler.php');
require_once(dirname(__FILE__) . '/wp-content/mu-plugins/insta-wp/src/Modules/FastHttpHandler/AllowedMethods.php');
require_once(dirname(__FILE__) . '/wp-content/mu-plugins/insta-wp/src/Modules/ModuleInterface.php');

use InstaWP\Modules\FastHttpHandler\FastHttpHandler;

$fastHttpHandler = new FastHttpHandler();
$handleResult = $fastHttpHandler->handle($_SERVER['REQUEST_METHOD'], $_SERVER['HTTP_HOST']);

// Request was handled by fast router, abort
if ($handleResult) {
    exit;
}
