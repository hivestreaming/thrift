<?php

/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

namespace Test\Thrift\Unit;

use PHPUnit\Framework\TestCase;
use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Serializer\TBinarySerializer;

/***
 * This test suite depends on running the compiler against the ./Resources/ThriftTest.thrift file:
 * lib/php/test$ ../../../compiler/cpp/thrift --gen php -r  --out ./Resources/packages/php ./Resources/ThriftTest.thrift
 */
class BinarySerializerTest extends TestCase
{
    public function setUp()
    {
        $loader = new ThriftClassLoader();
        $loader->registerNamespace('ThriftTest', __DIR__ . '/../Resources/packages/php');
        $loader->registerDefinition('ThriftTest', __DIR__ . '/../Resources/packages/php');
        $loader->register();
    }

    /**
     * We try to serialize and deserialize a random object to make sure no exceptions are thrown.
     * @see THRIFT-1579
     */
    public function testBinarySerializer()
    {
        $struct = new \ThriftTest\Xtruct(array('string_thing' => 'abc'));
        $serialized = TBinarySerializer::serialize($struct, 'ThriftTest\\Xtruct');
        $deserialized = TBinarySerializer::deserialize($serialized, 'ThriftTest\\Xtruct');
        $this->assertEquals($struct, $deserialized);
    }
}
