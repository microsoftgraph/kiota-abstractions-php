<?php

namespace Microsoft\Kiota\Abstractions\Tests\Serialization;

use DateInterval;
use DateTime;
use Microsoft\Kiota\Abstractions\Serialization\SerializationWriterToStringTrait;
use PHPUnit\Framework\TestCase;

class SerializationWriterToStringTraitTest extends TestCase
{
    public function testGetDateIntervalValueAsString(): void
    {
        $serializationWriter = new class {
            use SerializationWriterToStringTrait;
        };
        $dateInterval = new DateInterval('P1D');
        $dateInterval->invert = 1;
        $dateInterval2 = new DateInterval('P1DT11S');
        $dateInterval1ToString = $serializationWriter->getDateIntervalValueAsString($dateInterval);
        $dateInterval2ToString = $serializationWriter->getDateIntervalValueAsString($dateInterval2);
        $this->assertEquals('-P1D', $dateInterval1ToString);
        $this->assertEquals('P1DT11S', $dateInterval2ToString);
    }

    public function testGetDateTimeValueAsString(): void
    {
        $serializationWriter = new class {
            use SerializationWriterToStringTrait;
        };
        $dateTime = new DateTime('2024-04-29T15:12');
        $dateTimeString = $serializationWriter->getDateTimeValueAsString($dateTime);
        $this->assertEquals('2024-04-29T15:12:00+00:00', $dateTimeString);
    }

    public function testGetBooleanValueAsString(): void
    {
        $serializationWriter = new class {
            use SerializationWriterToStringTrait;
        };

        $booleanValue = $serializationWriter->getBooleanValueAsString(true);
        $this->assertEquals('true', $booleanValue);
        $booleanValue = $serializationWriter->getBooleanValueAsString(false);
        $this->assertEquals('false', $booleanValue);
    }
}
