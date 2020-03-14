<?php

 function updatedPagination($url){
    // For Pagination
            $ch_header = curl_init($url);
            curl_setopt_array($ch_header,array(
    
                CURLOPT_POST => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HEADER         => TRUE,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                )
            ));
            $response_head = curl_exec($ch_header);
            $header_size = curl_getinfo($ch_header, CURLINFO_HEADER_SIZE);
            $headers = substr($response_head, 0, $header_size);
            $body = substr($response_head, $header_size);
            $response = $body;
            print_r($response);
            $headers_arr = explode("\r\n", $headers); // The seperator used in the Response Header is CRLF (Aka. \r\n)
    
    

            $splittingPrevNex=preg_split("/,/",$headers_arr[18]);    
            $makingCondition=preg_split("/rel=/",$splittingPrevNex[0]);
            $makingCondition=preg_replace('/"/',"",$makingCondition[1]);
    
            if($makingCondition=="previous" and sizeof($splittingPrevNex) > 1) {
    //            it means we have both next and previous links
    
                $splittingPrevLinks=preg_split("/;/",$splittingPrevNex[0]);
                $prev_page_data=preg_split('/\&/',$splittingPrevLinks[0]);
                $pattern='/page_info=/';
                $replacement='';
                $prev_page_info=preg_replace($pattern, $replacement, $prev_page_data);
                $pattern='/>/';
                $replacement='';
                $prev_page_info=preg_replace($pattern, $replacement, $prev_page_info[1]);
    
    
                $splittingNextLinks=preg_split("/;/",$splittingPrevNex[1]);
                $next_page_data=preg_split('/\&/',$splittingNextLinks[0]);
                $pattern='/page_info=/';
                $replacement='';
                $next_page_info=preg_replace($pattern, $replacement, $next_page_data);
                $pattern='/>/';
                $replacement='';
                $next_page_info=preg_replace($pattern, $replacement, $next_page_info[1]);

            }

            elseif($makingCondition=="previous" and !(sizeof($splittingPrevNex)>1)){
    //            we have only previous links
    
                $splittingPrevLinks=preg_split("/;/",$splittingPrevNex[0]);
                $prev_page_data=preg_split('/\&/',$splittingPrevLinks[0]);
                $pattern='/page_info=/';
                $replacement='';
                $prev_page_info=preg_replace($pattern, $replacement, $prev_page_data);
                $pattern='/>/';
                $replacement='';
                $prev_page_info=preg_replace($pattern, $replacement, $prev_page_info[1]);
    
    
            }else{
    //             we have only next links
    
                $splittingNextLinks=preg_split("/;/",$splittingPrevNex[0]);
                $next_page_data=preg_split('/\&/',$splittingNextLinks[0]);
    
                $pattern='/page_info=/';
                $replacement='';
                $next_page_info=preg_replace($pattern, $replacement, $next_page_data);
                $pattern='/>/';
                $replacement='';
                $next_page_info=preg_replace($pattern, $replacement, $next_page_info[1]);
            }
    
            $responseData = json_decode($response, TRUE);
            $responseData['next_page']=$next_page_info;
            $responseData['prev_page']=$prev_page_info;
    
            return $responseData;
    
        }

?>