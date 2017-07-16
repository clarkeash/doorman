<?php

namespace Clarkeash\Doorman\Drivers;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

/**
 * Class UuidDriver
 *
 * @package \Clarkeash\Doorman\Drivers
 */
class UuidDriver implements DriverInterface
{
    /**
     * Create an invite code.
     *
     * @return string
     */
    public function code(): string
    {
        $version = config('doorman.uuid.version', 4);

        $method = 'createVersion' . $version . 'Uuid';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new InvalidArgumentException("Version [$version] not supported.");
    }

    protected function createVersion1Uuid(): string
    {
        return Uuid::uuid1()->toString();
    }

    protected function createVersion3Uuid(): string
    {
        throw_unless(config('doorman.uuid.namespace'), InvalidArgumentException::class, 'Namespace must be set for uuid version 3');
        throw_unless(config('doorman.uuid.name'), InvalidArgumentException::class, 'Name must be set for uuid version 3');

        return Uuid::uuid3(config('doorman.uuid.namespace'), config('doorman.uuid.namespace'))->toString();
    }

    protected function createVersion4Uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    protected function createVersion5Uuid(): string
    {
        throw_unless(config('doorman.uuid.namespace'), InvalidArgumentException::class, 'Namespace must be set for uuid version 5');
        throw_unless(config('doorman.uuid.name'), InvalidArgumentException::class, 'Name must be set for uuid version 5');

        return Uuid::uuid5(config('doorman.uuid.namespace'), config('doorman.uuid.namespace'))->toString();
    }
}
