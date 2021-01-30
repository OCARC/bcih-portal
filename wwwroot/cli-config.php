<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// cli-config.php
require_once "bootstrap.php";

return ConsoleRunner::createHelperSet($app::entityManager());