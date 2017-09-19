<?php

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-18T18:01:57+0800
     * @param    [type]                   $birthday [年龄的格式是：2016-09-23]
     * @return   [type]                             [description]
     */
    function calcAge($birthday) {  
        $age = 0;  
        if(!empty($birthday)){  
            $age = strtotime($birthday);  
            if($age === false){  
                return 0;  
            }  
              
            list($y1,$m1,$d1) = explode("-",date("Y-m-d", $age));  
              
            list($y2,$m2,$d2) = explode("-",date("Y-m-d"), time()); 
              
            $age = $y2 - $y1;  
            if((int)($m2.$d2) < (int)($m1.$d1)){  
                $age -= 1;  
            }  
        }  
        return $age;  
    }

    function getUploadFileName($hint = '')
    {
        $time = date('Y/m/d-H:i:s');  
        return $hint . $time;
    }