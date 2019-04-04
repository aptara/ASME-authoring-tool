<?php

namespace App\Modules\Mail\Service;

interface SendMailInterface
{
    public function mail(array $options);
}
