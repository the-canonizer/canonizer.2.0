<?php

namespace App\Helpers;

/*
|=================================================================
| @Class        :   Util
| @Description  :   List all utility methods of Application
| @Author       :   Ashish Upadhyay
| @Created_at   :   14-Dec-2021
| @Modified_at  :
| @Version      :   1.0
|=================================================================
*/

class Util
{
    /**
     * Excute the http calls 
     * @param string $type (GET|POST|PUT|DELETE)
     * @param string $url
     * @param string $headers (Optional)
     * @param array $body (Optional)
     * @return mixed
     */
    public function execute($type, $url, $headers=null, $body=null) {
        $options = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => $type,
            CURLOPT_POSTFIELDS      => $body,
            CURLOPT_HTTPHEADER      => $headers
        );
        
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $curl_response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        } 
        else {
            //$curl_result_obj = $curl_response;
            return $curl_response;
        }
    }
}