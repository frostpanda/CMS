<?php

namespace App\Utils;

use App\Utils\AppMessages;

class UrlHandler {

    private $appMessages;

    function __construct(AppMessages $appMessages) {
        $this->appMessages = $appMessages;
    }

    public function verifyUrlParameter($urlParameter) {
        if (empty($urlParameter)){
            $this->appMessages->displayWarningMessage('Invalid URL parameter!');
            return true;
        } else {
            return false;
        }
    }

}
