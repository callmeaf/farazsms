<?php

namespace Callmeaf\Farazsms\Services\V1;

use Callmeaf\Farazsms\Services\V1\Contracts\FarazsmsServiceInterface;
use Callmeaf\Sms\Services\V1\SmsService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FarazsmsService extends SmsService implements FarazsmsServiceInterface
{
    public function getApiKey(): string
    {
        return config('callmeaf-farazsms.api_key');
    }

    public function getApiUrl(string $append = ''): string
    {
        return config('callmeaf-farazsms.api_url') .'/'. $append;
    }

    public function http(): PendingRequest
    {
        return Http::baseUrl($this->getApiUrl())->withHeaders([
            'apikey' => $this->getApiKey(),
        ])->withUrlParameters([
            'sender' => $this->fromNumber(),
        ]);
    }

    public function fromNumber(): string
    {
        return config('callmeaf-farazsms.from_number');
    }

    public function send(string $mobile, string $message): Response
    {
        $data = [
            'recipient' => [
                $mobile,
            ],
            'message' => $message,
        ];
        $response =  $this->http()->post('/sms/send/webservice/single',$data);

        $body = json_decode($response->body(),true);
        $result = $body['return'];
        if(@$result['status'] !== '200') {
            throw new \Exception(@$result['message'] ?? __('callmeaf-base::v1.unknown_error'));
        }

        return $response;
    }

    public function multiSend(array|string $mobiles, array|string $messages, array|string $senders): Response
    {
        $senders = @$senders[0];
        $messages = @$messages[0];
        $data = [
            'receptor' => $mobiles,
            'sender' => $senders,
            'message' => $messages,
        ];
        $response =  $this->http()->post('/sms/send/webservice/single',$data);

        $body = json_decode($response->body(),true);
        $result = $body['return'];
        if(@$result['status'] !== '200') {
            throw new \Exception(@$result['message'] ?? __('callmeaf-base::v1.unknown_error'));
        }

        return $response;
    }

    public function sendViaPattern(string $pattern, string $mobile, array $values = []): Response
    {
        $keys = config('callmeaf-farazsms.patterns.verify_otp.keys');
        $data = [
            'recipient' => $mobile,
            'code' => $pattern,
        ];
        foreach ($values as $index => $value) {
            if(!isset($keys[$index])) {
                break;
            }
            $data[$keys[$index]] = $value;
        }
        $response = $this->http()->post('/sms/pattern/normal/send', $data);
        Log::alert($response->status());
        Log::alert($response->body());
        Log::alert(json_encode($response->headers()));
        $body = json_decode($response->body(),true);
        $result = $body['return'];
        if(@$result['status'] !== '200') {
            throw new \Exception(@$result['message'] ?? __('callmeaf-base::v1.unknown_error'));
        }

        return $response;
    }

    public function verifyOtpPattern(): string
    {
        return config('callmeaf-farazsms.patterns.verify_otp.template');
    }

    public function verifyForgotPasswordCodePattern(): string
    {
        return config('callmeaf-farazsms.patterns.verify_forgot_password_code.template');
    }
}
