<?php

namespace Yansongda\Pay\Tests;

use Yansongda\Pay\Contracts\GatewayApplicationInterface;
use Yansongda\Pay\Exceptions\GatewayException;
use Yansongda\Pay\Pay;

class PayTest extends TestCase
{
    public function testAlipayGateway()
    {
        $alipay = Pay::alipay(['foo' => 'bar']);

        $this->assertInstanceOf(GatewayApplicationInterface::class, $alipay);
    }

    public function testWechatGateway()
    {
        $wechat = Pay::wechat(['foo' => 'bar']);

        $this->assertInstanceOf(GatewayApplicationInterface::class, $wechat);
    }

    public function testFooGateway()
    {
        $this->expectException(GatewayException::class);
        $this->expectExceptionMessage('Gateway [foo] Not Exists');

        Pay::foo([]);
    }

    private $alipayConf = [
        'app_id' => '2017103009616316',
        'private_key' => '自己生成的私钥',
        'ali_public_key' => '支付宝公钥',
    ];

    public function testInitializeCertifyAlipayGateway()
    {

        $order = [
            'outer_order_no' => time().mt_rand(1,999),
            'biz_code' => 'FACE',
            'identity_param' => [
                'identity_type' => 'CERT_INFO',
                'cert_type' => 'IDENTITY_CARD',
                'cert_name' => '姓名',
                'cert_no' => '身份证号'
            ],
            'merchant_config' => [
                'return_url' => "http://www.baidu.com"
            ],
        ];
        $alipay = Pay::alipay($this->alipayConf)->initializeCertify($order);
        var_dump($alipay);
        $this->assertJson($alipay);
    }

    public function testStartCertifyAlipayGateway()
    {

        $order = [
            'certify_id' => "ec347804143905b924d2657fbe268a59",
        ];
        $getUrl = Pay::alipay($this->alipayConf)->startCertify($order);
        print_r($getUrl);
        $this->assertIsString($getUrl);
    }

    public function testQueryCertifyAlipayGateway()
    {

        $order = [
            'certify_id' => "ec347804143905b924d2657fbe268a59",
        ];
        $alipay = Pay::alipay($this->alipayConf)->queryCertify($order);
        print_r($alipay); // {"code":"10000","msg":"Success","passed":"T","material_info":"{}"}
        $this->assertIsArray($alipay);
    }

}
