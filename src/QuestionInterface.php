<?php

namespace LawnGnome\Questionator;

use DateTimeInterface;

interface QuestionInterface {
  public function getID();
  public function getName(): string;
  public function getQuestion(): string;
  public function getTime(): DateTimeInterface;
  public function setID(int $id);
  public function setName(string $name);
  public function setQuestion(string $question);
  public function setTime(DateTimeInterface $time);
}
