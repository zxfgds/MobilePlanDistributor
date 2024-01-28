<?php

namespace app\service\Platform\Providers\Mini;

use app\service\Platform\Traits\WechatTrait;
use app\service\Platform\Providers\Serve;

abstract class Base extends Serve
{
    public const name = 'mini';

    // 获取用户信息
    abstract public function codeToUser($code): array;

    // 获取手机号码
    abstract public function getPhoneNumber($code, $encryptedData, $iv): array;

    // 获取分享链接
    abstract public function getShareUrl($path, $query = NULL): string;

    // 获取二维码
    abstract protected function getQrCode($path, $width = 430): string;

}