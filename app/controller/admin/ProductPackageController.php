<?php

namespace app\controller\admin;

use app\library\HttpCode;
use app\library\Validate;
use app\logic\ProductPackageLogic;
use support\Response;

class ProductPackageController extends Controller
{
    protected string $logic = ProductPackageLogic::class;
    
    protected array $rules = [
        'name'     => ['required' => TRUE],
        'cover'    => ['required' => TRUE],
        'operator' => ['required' => TRUE, 'min' => 1],
    ];
    
    
    /**
     * 分步表单
     * @return Response
     */
    public function step(): Response
    {
        //name、operator、type、cover、status、standard_config、price、price_show、interceptor、agreement、stock_status、stock_num、shop_id、select_num_status、select_num_config、carrier_status、carrier_config、code_remote,images,form_config,page_config,commission_total、commission_condition、commission_config,buy_num_valid_status、buy_num_valid_config,local_valid_config、remote_api_status、remote_api_config,recharge_status、recharge_config、pre_charge_status、pre_charge_amount,active_url
        $data   = $this->params();
        $detail = call_user_func_array([$this->logic, 'step'], [$data['step'] ?? 0, $data['id'] ?? 0]);
        return $this->success($detail);
    }
    
    /**
     *
     * 获取模板数据
     * @return Response
     */
    public function template(): Response
    {
        // 调用逻辑层的 defaultData 方法获取模板数据，返回成功响应
        return $this->success(call_user_func([$this->logic, 'defaultData']));
    }
    
    /**
     * type shop_id carrier_status、carrier_config、
     * 基本信息填写
     * 填写字段：name、operator、cover、status、standard_config、price、price_show、interceptor、agreement、stock_status、stock_num、select_num_status、select_num_config、buy_num_valid_status、buy_num_valid_config,images
     * 表单配置 form_config  page_config 验证配置 local_valid_config、remote_api_status、remote_api_config code_remote 佣金配置
     * 填写字段：commission_total、commission_condition、commission_config 激活预充 ：pre_charge_amount
     * active_url,pre_charge_status 返费配置  recharge_status、recharge_config 这样拆分可以根据用户的需求，在不同的步骤中分别填写相关的字段，提高填写的效率和用户的体验。
     */
}