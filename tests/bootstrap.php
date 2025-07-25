<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Brain\Monkey;

// Set up Brain Monkey before each test
\Brain\Monkey\setUp();

// Tear down Brain Monkey after each test
Brain\Monkey\tearDown();
