<?php

namespace App\Tests;


use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class DeserializerDiscriminatorTest extends TestCase
{


    public function testDeserializePropertyOnAbstract()
    {
        $json = json_encode([
            'discr' => 'concrete',
            'stringOnAbstractSubject' => 'str',
            'dummyOnAbstractSubject' => [
                'value' => 123
            ]
        ]);

        /** @var ConcreteSubject $subject */
        $subject = $this->serializer->deserialize($json, AbstractSubject::class, 'json');

        $this->assertInstanceOf(AbstractSubject::class, $subject);
        $this->assertInstanceOf(ConcreteSubject::class, $subject);
        $this->assertEquals('str', $subject->getStringOnAbstractSubject());
        $this->assertInstanceOf(DummySubject::class, $subject->getDummyOnAbstractSubject());
        $this->assertEquals(123, $subject->getDummyOnAbstractSubject()->getValue());
    }


    public function testDeserializePropertyOnConcrete()
    {
        $json = json_encode([
            'discr' => 'concrete',
            'stringOnConcreteSubject' => 'str',
            'dummyOnConcreteSubject' => [
                'value' => 123
            ]
        ]);

        /** @var ConcreteSubject $subject */
        $subject = $this->serializer->deserialize($json, AbstractSubject::class, 'json');

        $this->assertInstanceOf(AbstractSubject::class, $subject);
        $this->assertInstanceOf(ConcreteSubject::class, $subject);
        $this->assertEquals('str', $subject->getStringOnConcreteSubject());
        $this->assertInstanceOf(DummySubject::class, $subject->getDummyOnConcreteSubject());
        $this->assertEquals(123, $subject->getDummyOnConcreteSubject()->getValue());

    }


    public function testPatchForDeserializePropertyOnConcrete()
    {
        $json = json_encode([
            'discr' => 'concrete',
            'stringOnConcreteSubject' => 'str',
            'dummyOnConcreteSubject' => [
                'value' => 123
            ]
        ]);

        /** @var ConcreteSubject $subject */
        $subject = $this->patched_serializer->deserialize($json, AbstractSubject::class, 'json');

        $this->assertInstanceOf(AbstractSubject::class, $subject);
        $this->assertInstanceOf(ConcreteSubject::class, $subject);
        $this->assertEquals('str', $subject->getStringOnConcreteSubject());
        $this->assertInstanceOf(DummySubject::class, $subject->getDummyOnConcreteSubject());
        $this->assertEquals(123, $subject->getDummyOnConcreteSubject()->getValue());

    }


    /** @var Serializer */
    private $serializer;


    /** @var Serializer */
    private $patched_serializer;


    public function setUp()
    {
        $this->serializer = $this->createSerializer(ObjectNormalizer::class);
        $this->patched_serializer = $this->createSerializer(Patched_ObjectNormalizer::class);
    }

    private function createSerializer(string $object_normalizer_class): Serializer
    {
        $loader = new AnnotationLoader(new AnnotationReader());
        $classMetadataFactory = new ClassMetadataFactory($loader);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $reflectionExtractor = new ReflectionExtractor();
        $listExtractors = array($reflectionExtractor);
        $typeExtractors = array($reflectionExtractor);
        $accessExtractors = array($reflectionExtractor);
        $propertyTypeExtractor = new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            [],
            $accessExtractors
        );
        $discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);
        $objectNormalizer = new $object_normalizer_class($classMetadataFactory, null, $propertyAccessor, $propertyTypeExtractor, $discriminator);
        return new Serializer(
            [
                $objectNormalizer
            ],
            [new JsonEncoder()]
        );

    }


}
