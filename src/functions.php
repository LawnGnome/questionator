<?php

namespace LawnGnome\Questionator;

function dsn(): string {
  return 'sqlite:'.__DIR__.'/../questionator.db';
}
