<?php

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-18T18:01:57+0800
     * @param    [type]                   $birthday [年龄的格式是：2016-09-23]
     * @return   [type]                             [description]
     */
    function calcAge($birthday) {
        if (is_null($birthday)) {
            return '--';
        }  
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

    /**
    * 计算.生肖
    * 
    * @param $birthday 年龄的格式是：2016-09-23
    * @return str
    */
    function get_animal($birthday='0000-00-01'){
        if (is_null($birthday)) {
            return '--';
        }
        $array = explode("-",$birthday);
        $year = $array[0];
        $animals = array(
        '鼠', '牛', '虎', '兔', '龙', '蛇', 
        '马', '羊', '猴', '鸡', '狗', '猪'
        );
        $key = ($year - 1900) % 12;
        return $animals[$key];
    }

    /**
    * 计算.星座
    *
    * @param $birthday 年龄的格式是：2016-09-23
    * @return str
    */
    function get_constellation($birthday='0000-00-01'){
        if (is_null($birthday)) {
            return '--';
        }
        $array = explode("-",$birthday);
        var_dump($birthday);
        exit();
        $month = $array[1];
        $day = $array[2];
        $signs = array(
            array('20'=>'宝瓶座'), array('19'=>'双鱼座'),
            array('21'=>'白羊座'), array('20'=>'金牛座'),
            array('21'=>'双子座'), array('22'=>'巨蟹座'),
            array('23'=>'狮子座'), array('23'=>'处女座'),
            array('23'=>'天秤座'), array('24'=>'天蝎座'),
            array('22'=>'射手座'), array('22'=>'摩羯座')
        );
        $key = (int)$month - 1;
        list($startSign, $signName) = each($signs[$key]);
        if( $day < $startSign ){
        $key = $month - 2 < 0 ? $month = 11 : $month -= 2;
        list($startSign, $signName) = each($signs[$key]);
        }
        return $signName;
    }