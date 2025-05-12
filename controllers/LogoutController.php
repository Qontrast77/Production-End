<?php
require_once "BaseCrazyTwigController.php"; 

class LogoutController extends BaseCrazyTwigController {
    
    public function post(array $context) {
      
        $_SESSION["is_logged"] = false;
        header("Location: /login");
        exit;
    }
}