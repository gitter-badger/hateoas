<?php
/**
 * @copyright 2014 Integ S.A.
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @author Tito Miguel Costa <titomiguelcosta@gmail.com>
 */

namespace GoIntegro\Hateoas\JsonApi\Serializer;

// Mocks.
use Codeception\Util\Stub;

class ResourceObjectSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \GoIntegro\Hateoas\JsonApi\Serializer\InvalidFieldException
     */
    public function testSerializingWithUnknownFieldThrowsException()
    {
        /* Given... (Fixture) */
        $metadata = Stub::makeEmpty(
            'GoIntegro\Hateoas\Metadata\Resource\ResourceMetadata',
            [
                'type' => 'type',
                'subtype' => 'users',
                'isRelationship' => function () {
                    return false;
                }

            ]
        );
        $entityResource = Stub::makeEmpty(
            'GoIntegro\Hateoas\JsonApi\EntityResource',
            [
                'id' => '10',
                'getMetadata' => function () use ($metadata) {
                    return $metadata;
                },
                'isFieldBlacklisted' => function () {
                    return false;
                },
                'callGetter' => function () {
                    throw new \Exception();
                }
            ]
        );
        $serializer = new ResourceObjectSerializer(
            $entityResource,
            self::buildSecurityContext(),
            ['id']
        );
        /* When... (Action) */
        $serializer->serialize();
    }

    /**
     * @return \Symfony\Component\Security\Core\SecurityContextInterface
     */
    public static function buildSecurityContext()
    {
        return Stub::makeEmpty(
            'Symfony\\Component\\Security\\Core\\SecurityContextInterface',
            ['isGranted' => TRUE]
        );
    }
}
