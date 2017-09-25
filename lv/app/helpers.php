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
        $time = date('YmdHis');  
        return $hint . $time;
    }

    function sendMsg($to_uid = 0, $msg = '')
    {
        // 推送的url地址，上线时改成自己的服务器地址
        $push_api_url = "http://lv.dev:2121/";
        $post_data = array(
           'type' => 'publish',
           'content' => $msg,
           'to' => $to_uid, 
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
       // var_export($return);
    }