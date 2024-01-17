<?php

namespace NieFufeng\Express\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JetBrains\PhpStorm\ArrayShape;
use NieFufeng\Express\Enums\BirdShipperCodes;
use NieFufeng\Express\Enums\RequestTypes;
use NieFufeng\Express\Exceptions\ApiException;
use NieFufeng\Express\Exceptions\InvalidArgumentException;
use NieFufeng\Express\Exceptions\RequestException;

/**
 * 快递鸟
 */
class Bird
{
    protected string $api = 'https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';

    public function __construct(
        protected readonly string $appId,
        protected readonly string $appKey,
    ) {
    }

    /**
     * @param  string  $trackingCode  快递单号
     * @param  BirdShipperCodes|null  $shipperCode  快递代码（请求类型为 StandardEdition 时可为null）
     * @param  string  $customer  寄件人或收件人手机号后四位，顺丰必填，其它快递可忽略
     * @param  RequestTypes  $type  接口指令
     *
     * @throws ApiException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    #[ArrayShape([
        'StateEx' => 'integer',
        'ShipperCode' => 'string',
        'Traces' => 'array',
        'EBusinessID' => 'string',
        'DeliveryMan' => 'string',
        'DeliveryManTel' => 'string',
        'Success' => 'boolean',
        'ReceiverCityLatAndLng' => 'string',
        'LogisticCode' => 'string',
        'State' => 'integer',
        'SenderCityLatAndLng' => 'string',
        'Location' => 'string',
    ])]
    public function track(string $trackingCode, ?BirdShipperCodes $shipperCode, RequestTypes $type = RequestTypes::StandardEdition, string $customer = ''): array
    {
        $trackingCode = trim($trackingCode);

        if (empty($trackingCode)) {
            throw new InvalidArgumentException('请输入快递单号');
        }

        if ($shipperCode === BirdShipperCodes::SF && empty($customer)) {
            throw new InvalidArgumentException('顺丰快递需要传递 customer');
        }

        $data = [
            'LogisticCode' => $trackingCode,
            'ShipperCode' => $shipperCode?->value ?? '',
            'OrderCode' => '',
            'CustomerName' => $customer,
        ];

        $form = [
            'EBusinessID' => $this->appId,
            'RequestType' => (string) $type->value,
            'RequestData' => urlencode(json_encode($data)),
            'DataType' => '2',
            'DataSign' => $this->encrypt($data),
        ];

        try {
            $result = json_decode($this->getHttpClient()->request('POST', $this->api, [
                RequestOptions::FORM_PARAMS => $form,
            ])->getBody()->getContents(), true);

            if ($result['Success']) {
                return $result;
            }

            throw new ApiException($result['Reason'], $result);
        } catch (GuzzleException $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function encrypt(array $data): string
    {
        return urlencode(base64_encode(md5(json_encode($data).$this->appKey)));
    }

    private function getHttpClient(): Client
    {
        return new Client();
    }
}
