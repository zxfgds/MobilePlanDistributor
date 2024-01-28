<?php

namespace app\controller\admin;

use app\library\ImageProcessor;
use app\logic\AttachmentLogic;
use support\Log;

class ToolController extends Controller
{
    
    
    public function imageSplit(): \support\Response
    {
        $data = request()->all();
        
        if (empty($data['path'])) return $this->error('请选择图片');
        
        $image = storagePath($data['path']);
        
        if (!file_exists($image)) return $this->error('图片不存在');
        
        try {
            $array = [];
            
            $images = ImageProcessor::splitImage($image, $data['headerHeight']);

            $headerData = empty($images['header']) ? ['url' => ''] : AttachmentLogic::upload($images['header']);
            
            $array['header'] = $headerData['url'];
            
            foreach ($images['images'] as $img) {
                $imageData         = AttachmentLogic::upload($img);
                $array['images'][] = $imageData['url'];
            }
            
            return $this->success($array);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error(env('APP_DEBUG') ? $e->getMessage() : '系统 错误');
        }
    }
}