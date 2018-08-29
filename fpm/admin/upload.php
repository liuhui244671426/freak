<?php
class fpm_admin_upload extends fpm_base{
    public function init(){}

    public function file(){
        $upload = new lib_upload();
        $upload->upload('file');
        $err = $upload->getErrorInfo();
        $suc = $upload->getSuccessInfo();
        $out = [];
        if($err){
            $out['status'] = -1;
            $out['data'] = $err;
        }
        if($suc){
            $out['status'] = 1;
            $out['data'] = $suc;
        }

        freak_output::json($out);
        return;
    }
}