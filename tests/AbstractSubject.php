<?php

namespace App\Tests;


use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 *
 * @DiscriminatorMap(typeProperty="discr", mapping={
 *     "concrete" = ConcreteSubject::class
 * })
 */
abstract class AbstractSubject
{

    private $dummyOnAbstractSubject;

    public function setDummyOnAbstractSubject(DummySubject $value): void
    {
        $this->dummyOnAbstractSubject = $value;
    }

    public function getDummyOnAbstractSubject(): DummySubject
    {
        return $this->dummyOnAbstractSubject;
    }


    private $stringOnAbstractSubject;

    public function setStringOnAbstractSubject(string $value): void
    {
        $this->stringOnAbstractSubject = $value;
    }

    public function getStringOnAbstractSubject(): string
    {
        return $this->stringOnAbstractSubject;
    }


}