<?php   

if(isset($_GET['key']))
{
    
    $query = 'SELECT id,
        first,
        last,
        email,
        github_username,
        url,
        avatar,
        admin,
        session_id
        FROM users
        WHERE id = "'.addslashes($_GET['key']).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $user = mysqli_fetch_assoc($result);

        $data = array(
            'message' => 'User retrieved successfully.',
            'error' => false, 
            'user' => $user,
        );

    }
    else
    {
        
        $data = array(
            'message' => 'User not found.',
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