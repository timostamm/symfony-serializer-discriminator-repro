<?php

namespace App\Tests;

class DummySubject
{

    private $value;

    public function setValue(int $number): void
    {
        $this->value = $number;
    }

    public function getValue(): int
    {
        return $this->value;
    }

}