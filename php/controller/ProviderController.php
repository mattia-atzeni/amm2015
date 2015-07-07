<?php
include_once 'BaseController.php';
include_once 'php/view/ViewDescriptor.php';

class ProviderController extends BaseController {
    public function handleInput() {
        $vd = new ViewDescriptor();
        $cmd = $_REQUEST['cmd'];
        
        switch ($cmd) {
            default: showHomePage();
        }
    }
}
