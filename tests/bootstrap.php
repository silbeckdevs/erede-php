<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

// for local development copy tests/config/env.test.php.example to tests/config/env.test.php and add your credentials
@include dirname(__DIR__) . '/tests/config/env.test.php';

date_default_timezone_set('America/Sao_Paulo');
