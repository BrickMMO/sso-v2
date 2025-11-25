<?php

$query = 'SELECT * 
    FROM users ';

if(isset($_GET['github']) && $_GET['github'] == 'true')
{
    $query .= 'WHERE github_username != "" ';
}

$query .= 'ORDER BY last,first'; 
$result = mysqli_query($connect, $query);

$users = array();

if(mysqli_num_rows($result))
{

    while($user = mysqli_fetch_assoc($result))
    {

        $users[]= $user;
        
    }

    $data = array(
        'message' => 'Users retrieved successfully.',
        'error' => false, 
        'users' => $users,
    );
    
}
else 
{

    $data = array(
        'message' => 'Error retrieving users.',
        'error' => true,
        'users' => null,
    );

}