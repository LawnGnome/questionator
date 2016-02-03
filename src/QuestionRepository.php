<?php

namespace LawnGnome\Questionator;

use DateTimeImmutable;
use DateTimeInterface;
use PDO;
use PDOException;

class QuestionRepository {
  public function __construct(PDO $db) {
    $this->db = $db;
  }

  public function all(): array {
    $stmt = $this->db->query('SELECT * FROM question ORDER BY time ASC');
    $questions = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $questions[] = $this->hydrate($row);
    }

    return $questions;
  }

  public function create(): QuestionInterface {
    $question = new Question;
    $question->setTime(new DateTimeImmutable);

    return $question;
  }

  public function delete(QuestionInterface $question): bool {
    $id = $question->getID();
    if (!is_int($id)) {
      return false;
    }

    $stmt = $this->db->prepare('DELETE FROM question WHERE id = ?');
    $stmt->execute([$id]);
    return true;
  }

  public function find(int $id) {
    $stmt = $this->db->prepare('SELECT * FROM question WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      return $this->hydrate($row);
    } else {
      return null;
    }
  }

  public function reset() {
    $this->db->query('DELETE FROM question');
  }

  public function save(QuestionInterface $question) {
    if (!is_int($question->getID())) {
      $stmt = $this->db->prepare('INSERT INTO question (name, question, time) VALUES (:name, :question, :time)');
      $stmt->execute([
        'name'     => $question->getName(),
        'question' => $question->getQuestion(),
        'time'     => $question->getTime()->getTimestamp(),
      ]);
      $question->setID($this->db->lastInsertId());
    } else {
      $stmt = $this->db->prepare('UPDATE question SET name = :name, question = :question, time = :time WHERE id = :id');
      $stmt->execute([
        'id'       => $question->getID(),
        'name'     => $question->getName(),
        'question' => $question->getQuestion(),
        'time'     => $question->getTime()->getTimestamp(),
      ]);
    }
  }

  public function createSchema() {
    $schema = <<<'EOF'
CREATE TABLE question (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  question TEXT NOT NULL,
  time INTEGER NOT NULL
);
EOF;
    $this->db->query($schema);
  }

  protected function hydrate(array $record): QuestionInterface {
    $question = new Question;
    $question->setID($record['id']);
    $question->setName($record['name']);
    $question->setQuestion($record['question']);
    $question->setTime(new DateTimeImmutable('@'.$record['time']));

    return $question;
  }
}
