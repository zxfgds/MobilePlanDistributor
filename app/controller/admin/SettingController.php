<?php

namespace app\controller\admin;

use app\logic\SettingLogic;
use support\Response;

class SettingController extends Controller
{
    protected string $logic = SettingLogic::class;

    /**
     * 根据类别和关键字检索设置。
     *
     * @return Response 返回响应对象以返回检索到的设置数据。
     */
    public function get(): Response
    {
        // 从请求中获取参数。
        $data = $this->params();
        // 从参数中获取类别值，否则将其设置为 NULL。
        $category = $data['category'] ?? NULL;
        // 从参数中获取关键字值，否则将其设置为 NULL。
        $key = $data['key'] ?? NULL;
        // 检查类别和关键字是否都为 NULL。
        if ($category == NULL && $key == NULL) {
            // 返回一个错误响应，消息为 '无效的参数'。
            return $this->error('无效的参数');
        }

        // 调用 $this->logic 的一个方法，将类别和关键字作为其参数。
        $settings = call_user_func_array([$this->logic, 'get'], [$category, $key]);

        // 检查设置是否为空且关键字不为空。
        if (empty($settings) && !empty($key)) {

            // 调用 $this->logic 的一个方法，将类别和关键字作为其参数。
            $defaultKeys = call_user_func_array([$this->logic, 'default'], [$category, $key]);
            // 检查 defaultKeys 是否为空。
            if (!empty($defaultKeys)) {
                // 创建一个空数组 $settings。
                $settings = [];
                // 循环遍历 defaultKeys，并将每个键在 $settings 中设置为 NULL 值。
                foreach ($defaultKeys as $key) {
                    $settings[$key] = NULL;
                }
            }
        }

        // 返回一个成功响应，其中的数据为 $settings。
        return $this->success($settings);
    }


    /**
     * 设置参数并返回响应
     *
     * @return Response HTTP响应实例
     */
    public function set(): Response
    {
        // 获取请求参数
        $data = $this->params();
        // 从参数中获取分类、键、值
        $category = $data['category'] ?? null;
        $key = $data['key'] ?? null;
        $value = $data['value'] ?? null;

        // 如果分类参数不存在，返回错误响应
        if (!$category) {
            return $this->error('参数错误');
        }

        try {
            // 如果键不为null，则设置键值对
            if ($key !== null) {
                $this->logic::set($category, $key, $value);
            } // 如果值是数组，则遍历数组中的每个元素，并进行设置
            elseif (is_array($value)) {
                foreach ($value as $k => $v) {
                    $this->logic::set($category, $k, $v);
                }
            }
            // 返回成功响应
            return $this->success();
        } // 捕获异常，返回错误响应
        catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}