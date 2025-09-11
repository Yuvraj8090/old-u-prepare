<?php

if (!function_exists('formatIndianNumber')) {

        function formatIndianNumber($num) {
            if ($num < 100000) {
                $format = number_format($num);
            } else {
                $format = number_format($num / 100000, 2) . ' Lakh';
            }
            return $format;
        }
    }
    
    
    if (!function_exists('formatIndianNumberLakh')) {
        
        function formatIndianNumberLakh($num) {
            if ($num < 1000) {
                $format = number_format($num);
            } else if ($num < 100000) {
                $format = number_format($num / 1000, 2) . ' Th';
            } else if ($num < 10000000) {
                $format = number_format($num / 100000, 2) . ' Lacs';
            } else {
                $format = number_format($num / 10000000, 2) . ' Cr';
            }
            return $format;
        }
    }
    
    
    if (!function_exists('CheckESLevelThree')) {
        
        function CheckESLevelThree() {
             
            $userRole = auth()->user()->role;
             
            if(in_array($userRole->department,['PWD-ENVIRONMENT','PMU-ENVIRONMENT','USDMA-ENVIRONMENT','RWD-ENVIRONMENT','FROEST-ENVIRONMENT'])){
                return '1';
            }elseif(in_array($userRole->department,['PWD-SOCIAL','PMU-SOCIAL','USDMA-SOCIAL','RWD-SOCIAL','FROEST-SOCIAL'])){
                return '2';
            }elseif(in_array($userRole->department,['ENVIRONMENT','SOCIAL'])){
                return '3';
            }else{
                return '0';
            }
          
        }
 
    }
    
    
    if (!function_exists('CheckEnvironmentLvlThree')) {
             function CheckEnvironmentLvlThree($department,$level = "THREE"){
            
                $status = false;
                
                $userRole = auth()->user()->role;
                
                if($userRole->level === $level && in_array($userRole->department,['PWD-ENVIRONMENT','PMU-ENVIRONMENT','USDMA-ENVIRONMENT','RWD-ENVIRONMENT','FROEST-ENVIRONMENT'])){
                   $status = true; 
                }
                
                return $status;
            }
            
     }
    
    
       if (!function_exists('CheckSocialLvlThree')) {
                function CheckSocialLvlThree($department,$level= "THREE"){
        
                $status = false;
                
                $userRole = auth()->user()->role;
                
                if($userRole->level === $level && in_array($userRole->department,['PWD-SOCIAL','PMU-SOCIAL','USDMA-SOCIAL','RWD-SOCIAL','FROEST-SOCIAL'])){
                   $status = true; 
                }
                
                return $status;
         }
       }
       
       
        if (!function_exists('render_document_links')) {
           function render_document_links($media_path, $media_name) {
                    if (isset($media_name)) {
                        $viewUrl = url($media_path . '/' . $media_name);
                        echo "<a target='_blank' class='btn btn-md btn-primary' onClick='openPDF(\"$viewUrl\")' href='$viewUrl'>View Document</a>";
                        echo "<a download class='btn btn-md btn-danger' href='$viewUrl'>Download Document</a>";
                    } else {
                        echo "<h5 class='text-center'><b>Document upload in progress..</b></h5>";
                    }
            }
        }
        
        
         if (!function_exists('dateFormat')) {
           function dateFormat($date = NULL) {
                return $date ? date('d-m-Y',strtotime($date)) : 'N/A';
            }
        }
   
    

    
?>