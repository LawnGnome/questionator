<?php

namespace LawnGnome\Questionator;

use DateTime;
use DateTimeInterface;
use JsonSerializable;

class Question implements JsonSerializable, QuestionInterface {
  protected $id;
  protected $name;
  protected $question;
  protected $time;

  public function getID() {
    return $this->id;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getQuestion(): string {
    return $this->question;
  }

  public function getTime(): DateTimeInterface {
    return $this->time;
  }

  public function setID(int $id) {
    $this->id = $id;
  }

  public function setName(string $name) {
    $this->name = $name;
  }

  public function setQuestion(string $question) {
    $this->question = $question;
  }

  public function setTime(DateTimeInterface $time) {
    $this->time = clone $time;
  }

  public function jsonSerialize(): array {
    return [
      'id'       => $this->getID(),
      'name'     => $this->getName(),
      'question' => $this->getQuestion(),
      'time'     => $this->getTime()->format(DateTime::ISO8601),
    ];
  }
}
