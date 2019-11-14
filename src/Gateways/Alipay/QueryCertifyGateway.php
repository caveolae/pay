<?php

namespace Yansongda\Pay\Gateways\Alipay;

use Yansongda\Pay\Contracts\GatewayInterface;
use Yansongda\Pay\Log;

class QueryCertifyGateway implements GatewayInterface
{

    /**
     * Query Certify
     * @param string $endpoint
     * @param array $payload
     * @return array|string
     * @throws \Yansongda\Pay\Exceptions\GatewayException
     * @throws \Yansongda\Pay\Exceptions\InvalidArgumentException
     * @throws \Yansongda\Pay\Exceptions\InvalidConfigException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function pay($endpoint, array $payload): string
    {
        $payload['method'] = $this->getMethod();
        $payload['sign'] = Support::generateSign($payload);

        Log::info('Starting To Pay An Alipay App Order', [$endpoint, $payload]);

        $result = Support::requestApi($payload);

        return json_decode($result, true);
    }

    /**
     * Get method config.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return string
     */
    protected function getMethod(): string
    {
        return 'alipay.user.certify.open.query';
    }

    /**
     * Get productCode method.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return string
     */
    protected function getProductCode(): string
    {
        return 'QUICK_MSECURITY_PAY';
    }
}
