<?php

namespace Respect\Validation;

use stdClass;

/**
 * @covers Respect\Validation\Result
 */
class ResultTest extends TestCase
{
    public function testShouldAcceptArgumentsOnConstructor()
    {
        $rule       = $this->getMockRule();
        $properties = ['value' => new stdClass()];
        $factory    = $this->getFactoryMock();

        $result     = new Result($rule, $properties, $factory);

        $this->assertSame($rule, $result->getRule());
        $this->assertSame($properties['value'], $result->getProperties()['value']);
        $this->assertSame($factory, $result->getFactory());
    }
}
