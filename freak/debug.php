<?php
class freak_debug {
    public static function memory_usage(){
        $unit=array('b','kb','mb','gb','tb','pb');
        $mem_size = memory_get_usage(true);
        $peak_mem_size = memory_get_peak_usage(true);
        return [
            'usage' => round($mem_size/pow(1024,($i=floor(log($mem_size,1024)))),2).' '.$unit[$i],
            'peak_usage' => round($peak_mem_size/pow(1024,($i=floor(log($peak_mem_size,1024)))),2).' '.$unit[$i]
        ];
    }
}