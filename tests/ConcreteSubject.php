<?php

namespace App\Tests;


class ConcreteSubject extends AbstractSubject
{

    private $dummyOnConcreteSubject;

    public function setDummyOnConcreteSubject(DummySubject $value): void
    {
        $this->dummyOnConcreteSubject = $value;
    }

    public function getDummyOnConcreteSubject(): DummySubject
    {
        return $this->dummyOnConcreteSubject;
    }


    private $stringOnConcreteSubject;

    public function setStringOnConcreteSubject(string $value): void
    {
        $this->stringOnConcreteSubject = $value;
    }

    public function getStringOnConcreteSubject(): string
    {
        return $this->stringOnConcreteSubject;
    }

}