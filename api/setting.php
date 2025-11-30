<?php   

if(!security_api_ip())
{
    
    $data = array(
        'message' => 'Unauthorized IP address.',
        'error' => true, 
    );

}
elseif(isset($_GET['key']))
{
    
    $setting = setting_fetch($_GET['key']);

    if($setting)
    {

        $data = array(
            'message' => 'Setting retrieved successfully.',
            'error' => false, 
            'setting' => $setting,
        );

    }
    else
    {
        
        $data = array(
            'message' => 'Setting not found.',
            'error' => true, 
        );

    }

}
else
{

    $data = array(
        'message' => 'No user ID specified..',
        'error' => true, 
    );

}