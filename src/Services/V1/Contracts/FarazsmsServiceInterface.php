<?php

namespace Callmeaf\Farazsms\Services\V1\Contracts;


use Callmeaf\Sms\Services\V1\Contracts\SmsServiceInterface;

interface FarazsmsServiceInterface extends SmsServiceInterface
{
    public function fromNumber(): string;
}
