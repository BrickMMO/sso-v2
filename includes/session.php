<?php

/*
 * Start the session
 */
session_set_cookie_params([
    'domain' => '.brickmmo.com',
    'path' => '/',
    'secure' => true,
    // 'httponly' => true,
    'samesite' => 'None',
]);

session_start();
