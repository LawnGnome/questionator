<?php

use LawnGnome\Questionator\QuestionRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\{Request, Response};

use function LawnGnome\Questionator\dsn;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application;

$app['db'] = $app->share(function (): PDO {
  $db = new PDO(dsn());
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $db;
});

$app['questions'] = $app->share(function (Application $app): QuestionRepository {
  return new QuestionRepository($app['db']);
});

$app->get('/', function (Request $request) use ($app): Response {
  return $app->redirect('/submit.html');
});

$app->get('/questions', function (Request $request) use ($app): Response {
  return $app->json($app['questions']->all());
});

$app->post('/question', function (Request $request) use ($app): Response {
  $question = $app['questions']->create();
  $question->setName(substr($request->request->get('name'), 0, 50));
  $question->setQuestion(substr($request->request->get('question'), 0, 500));
  $app['questions']->save($question);

  return $app->redirect('/thanks.html');
});

$app->run();
