<?php

namespace app\service\Platform\Providers\Mini;

use app\service\Platform\Traits\WechatTrait;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use Matrix\Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Wechat extends Base
{

    use WechatTrait;

    private array $config;

    /**
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $code
     *
     * @return array
     * @throws Exception
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function codeToUser($code): array
    {
        try {
            return $this->buildApp('mini', $this->config)->getUtils()->codeTosession($code);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getPhoneNumber($code, $encryptedData, $iv): array
    {
        // TODO: Implement getPhoneNumber() method.
    }

    public function getShareUrl($path, $query = NULL): string
    {
        // TODO: Implement getShareUrl() method.
    }

    protected function getQrCode($path, $width = 430): string
    {
        // TODO: Implement getQrCode() method.
    }

    public function userInfoFromCode($code): array
    {
        // TODO: Implement userInfoFromCode() method.
    }
}