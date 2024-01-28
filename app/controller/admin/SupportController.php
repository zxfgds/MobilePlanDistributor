<?php

namespace app\controller\admin;

use app\library\ColorAnalyzer;
use app\library\HttpCode;
use app\library\Validate;
use app\logic\ProductPackageLogic;
use app\logic\ProductFancyNumberLogic;
use app\model\Operator;
use app\model\Platform;
use app\model\Product;
use Exception;
use support\Log;
use support\Response;

class SupportController extends Controller
{
    
    public function productType(): Response
    {
        $array = [];
        foreach (Product::PRODUCTS as $key => $value) {
            $array[] = ['label' => $value, 'value' => $key];
        }
        return $this->success($array);
    }
    
    public function operator(): Response
    {
        $array = [];
        foreach (Operator::OPERATORS as $key => $value) {
            $array[] = ['label' => $value, 'value' => $key];
        }
        return $this->success($array);
    }
    
    public function Client(): Response
    {
        return $this->success(Platform::PLATFORM_NAMES);
    }
    
    /**
     * @throws Exception
     */
    public function getColor()
    {
        $data = $this->params();
        
        $errors = Validate::check($data, [
            'id'          => ['required' => TRUE],
            'type'        => ['required' => TRUE],
            'productType' => ['required' => TRUE],
        ]);
        
        if ($errors) return $this->error($errors, HttpCode::MISSING_REQUIRED_FIELD);
        
        $productType = (int)$data['productType'];
        
        $logic = match ($productType) {
            Product::PACKAGE => ProductPackageLogic::class,
            Product::VIRTUAL_NUMBER => ProductFancyNumberLogic::class,
            default => throw new Exception('Invalid product type'),
        };
        
        try {
            $product = $logic::getOne($data['id']);
            $images  = $product['images'];
            if (empty($images)) {
                return $this->error('产品主图未上传');
            }
            
            if ($data['type'] == 'main') $color = ColorAnalyzer::getMainColor($images);
            
            return $this->success(['color' => $color]);
            
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                $msg = sprintf(
                    "[%s][%s][%s:%s] %s\n%s",
                    __CLASS__,
                    __FUNCTION__,
                    $e->getFile(),
                    $e->getLine(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                );
                Log::error($msg); // 记录异常日志
                return $this->error($msg); // 返回详细的错误响应
            } else {
                Log::error($e->getMessage()); // 记录异常日志
                return $this->error($e->getMessage()); // 返回简略的错误响应
            }
        }
    }
    
    
    /**
     * 页面列表
     * @return Response
     */
    public function getPages(): Response
    {
        return $this->success([
            ['code' => 'index.index', 'label' => '首页'],
            ['code' => 'chat.index', 'label' => '客服'],
            ['code' => 'product.package.index', 'label' => '套餐列表'],
            ['code' => 'product.virtual-number.index', 'label' => '靓号列表'],
            ['code' => 'product.package.detail', 'label' => '套餐详情'],
            ['code' => 'product.virtual-number.detail', 'label' => '靓号详情'],
            ['code' => 'order.index', 'label' => '订单列表'],
            ['code' => 'customize.index', 'label' => '号码定制'],
            ['code' => 'help.index', 'label' => '帮助中心'],
            ['code' => 'news.index', 'label' => '文章资讯'],
            ['code' => 'my.index', 'label' => '个人中心'],
        ]);
    }
}