<?php

namespace app\library;

class AuthToken
{
    /**
     * 生成 JWT 令牌
     *
     * @param           $userId
     * @param float|int $exp
     *
     * @return string
     */
    public static function generateToken($userId, float|int $exp = 3600 * 24): string
    {
        // 获取私钥
        $secretKey = static::secretKey();
        // 生成 JWT header，使用 HS256 算法，类型为 JWT
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        // 生成 JWT payload，包含用户 ID 和过期时间
        $payload = json_encode(['userId' => $userId, 'exp' => time() + $exp]);
        // 对 header 进行 base64Url 编码
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        // 对 payload 进行 base64Url 编码
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        // 生成签名，使用 sha256 算法
        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, TRUE);
        // 对签名进行 base64Url 编码
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        // 返回最终的 JWT 令牌
        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }
    
    /**
     *
     * 验证 JWT 令牌是否有效
     *
     * @param string $token JWT 令牌
     *
     * @return mixed 如果令牌有效，则返回用户 ID；否则返回 false
     */
    public static function validateToken(string $token): mixed
    {
        $secretKey = static::secretKey(); // 获取应用密钥
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token); // 分离 JWT 令牌中的头部，载荷和签名
        $header            = json_decode(base64_decode(str_replace(['-', ''], ['+', '/'], $base64UrlHeader)), TRUE); // 解码并解析头部
        $payload           = json_decode(base64_decode(str_replace(['-', ''], ['+', '/'], $base64UrlPayload)), TRUE); // 解码并解析载荷
        $signature         = base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlSignature)); // 解码签名
        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, TRUE); // 计算预期签名
        if ($signature !== $expectedSignature) { // 如果签名不匹配，则说明令牌无效
            return FALSE;
        }
        if ($payload['exp'] < time()) { // 如果令牌过期，则说明令牌无效
            return FALSE;
        }
        return $payload['userId']; // 返回用户 ID
    }
    
    /**
    
    获取私钥
    @return bool|array|string 返回私钥，如果环境变量不存在，则返回false
     */
    private static function secretKey(): bool|array|string
    {
        return getenv('APP_SECRET_KEY');
    }
}
