<?php

namespace app\trait;

use Alipay\EasySDK\Kernel\Base;
use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Marketing;
use Alipay\EasySDK\Kernel\Member;
use Alipay\EasySDK\Kernel\Payment;
use Alipay\EasySDK\Kernel\Util;
use Matrix\Exception;

trait AlipayTrait
{


    /**
     * 构建支付宝应用.
     *
     * @param string $type 应用类型. Base Member Payment Marketing Util
     * https://opendocs.alipay.com/open/54/103419#Alipay%20Easy%20SDK%20API%20%E6%80%BB%E8%A7%88
     * @param array $config 应用配置.
     * @return Util|Member|Marketing|Base|Payment|null
     * @throws Exception
     */
    public function buildApp(string $type, array $config): Util|Member|Marketing|Base|Payment|null
    {
        // 设置选项.
        try {
            Factory::setOptions($this->getOptions($config));

            // 根据类型选择工厂方法创建应用程序实例.
            return match ($type) {
                'base' => Factory::base(),
                'member' => Factory::member(),
                'payment' => Factory::payment(),
                'marketing' => Factory::marketing(),
                'util' => Factory::util(),
                default => null,
            };
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * 获取选项
     * @param array $config 阿里支付的配置信息
     * @return Config 配置信息对象
     */
    protected function getOptions(array $config): Config
    {
        $options = new Config();
        $options->appId = $config['appid'];
        $options->protocol = 'https'; // 设置协议类型，默认为 https
        $options->gatewayHost = $config['gatewayHost'] ?? 'openapi.alipay.com'; // 设置网关地址，默认为 openapi.alipay.com
        $options->signType = 'RSA2'; // 设置签名方式，目前只支持 RSA2
        $options->merchantPrivateKey = $config['appPrivateKey']; // 商户私钥
        $options->merchantCertPath = storagePath('hidden/' . $config['appPublicCert']); // 商户证书路径
        $options->alipayCertPath = storagePath('hidden/' . $config['alipayPublicCert']); // 支付宝公钥证书路径
        $options->alipayRootCertPath = storagePath('hidden/' . $config['alipayRootCert']); // 支付宝根证书路径
        $options->notifyUrl = getenv('APP_URL') . '/notify/alipay'; // 支付宝服务器主动通知商户服务器里指定的页面http/https路径。
        $options->encryptKey = $config['aesKey']; // AES 加密密钥
        return $options;
    }

}