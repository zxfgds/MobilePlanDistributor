<?php

namespace app\controller\admin;

use app\logic\AttachmentLogic;

class AttachmentController extends Controller
{
    protected string $logic = AttachmentLogic::class;


    public function upload(): \support\Response
    {
        $data = $this->params();
        $file = request()->file("file");

        $hidden = isset($data['is_hidden']) ? $data['is_hidden'] : FALSE;

        return $this->success($this->logic::upload($file, !$hidden));
    }
}