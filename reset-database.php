<?php

use LawnGnome\Questionator\QuestionRepository;
use function LawnGnome\Questionator\dsn;

require_once __DIR__.'/vendor/autoload.php';

$db = new PDO(dsn());
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$repo = new QuestionRepository($db);
$repo->reset();
