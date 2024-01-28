<?php

namespace app\service\Platform\Providers\Official;

use app\trait\AlipayTrait;
use Matrix\Exception;
use RedisException;

class Alipay extends Base
{

    use AlipayTrait;

    protected array $config;

    public function __construct(array $config, $factory = 'base')
    {
        $this->config = $config;
    }


    /**
     * @throws RedisException
     */
    function getAuthUrl(): string
    {
        $url = getenv('APP_DEBUG') ? 'https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm' : 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';
        $url = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';
        // todo state 封装到合适的位置

        $queryData = [
            'app_id' => $this->config['appid'],
            'scope' => $this->scopes ? implode(',', $this->scopes) : 'auth_base',
            'redirect_uri' => $this->redirectUrl,
            'state' => $this->state ?? $this->createState()
        ];

        return $url . "?" . http_build_query($queryData);
    }

    /**
     * @param string $code
     * @return array
     * @throws Exception
     * @throws \Exception
     */
    function codeToUser(string $code): array
    {
        $res = $this->buildApp('base', $this->config)->oauth()->getToken($code);
        return ['openid' => $res->userId];
    }

    protected function formatConfig($config)
    {
        return $config;
    }
}