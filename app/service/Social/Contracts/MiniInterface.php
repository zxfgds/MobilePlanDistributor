<?php

namespace app\Service\Social\Contracts;

interface MiniInterface
{
    public function getShareUrl();
    
    public function getShareQrCode();
}