<?php

security_check();
admin_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']))
{
    message_set('User Error', 'There was an error with the provided user.');
    header_redirect('/admin/dashboard');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['first']) || 
        !validate_blank($_POST['last']) ||
        !validate_blank($_POST['email']) || 
        !validate_email($_POST['email']))
    {
        message_set('User Error', 'There was an error with the provided user information.', 'red');
        header_redirect('/admin/dashboard');
    }

    // Validate URL if provided
    if($_POST['url'])
    {
        if (!validate_alpha_numeric($_POST['url']) || 
            validate_reserved_urls($_POST['url']) ||
            validate_url_exists($_POST['url'], 'users', $_GET['key']))
        {
            message_set('URL Error', 'There was an error with the provided URL.', 'red');
            header_redirect('/admin/dashboard');
        }
    }
    
    $query = 'UPDATE users SET
        first = "'.addslashes($_POST['first']).'",
        last = "'.addslashes($_POST['last']).'",
        email = "'.addslashes($_POST['email']).'",
        url = "'.addslashes($_POST['url']).'",
        updated_at = NOW()
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('User Success', 'User has been successfully updated.');
    header_redirect('/admin/dashboard');
    
}

define('APP_NAME', 'My Account');
define('PAGE_TITLE', 'Edit User');
define('PAGE_SELECTED_SECTION', 'users');
define('PAGE_SELECTED_SUB_PAGE', '/admin/edit');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT *
    FROM users
    WHERE id = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);
$record = mysqli_fetch_assoc($result);

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/sso.png"
        height="50"
        style="vertical-align: top"
    />
    Single Sign On
</h1>
<p>
    <a href="/admin/dashboard">Single Sign On</a> / 
    Edit User
</p>

<hr>

<h2>Edit User: <?=$record['first']?> <?=$record['last']?></h2>

<!-- Edit form -->
<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="first" 
        class="w3-input w3-border" 
        type="text" 
        id="first" 
        autocomplete="off"
        value="<?=$record['first']?>"
    />
    <label for="first" class="w3-text-gray">
        First Name <span id="first-error" class="w3-text-red"></span>
    </label>

    <input  
        name="last" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="last" 
        autocomplete="off"
        value="<?=$record['last']?>"
    />
    <label for="last" class="w3-text-gray">
        Last Name <span id="last-error" class="w3-text-red"></span>
    </label>

    <input  
        name="email" 
        class="w3-input w3-border w3-margin-top" 
        type="email" 
        id="email" 
        autocomplete="off"
        value="<?=$record['email']?>"
    />
    <label for="email" class="w3-text-gray">
        Email <span id="email-error" class="w3-text-red"></span>
    </label>

    <input  
        name="url" 
        class="w3-input w3-border w3-margin-top" 
        type="text" 
        id="url" 
        autocomplete="off"
        value="<?=$record['url']?>"
    />
    <label for="url" class="w3-text-gray">
        URL <span id="url-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="validateMainForm(); return false;">
        <i class="fa-solid fa-pencil fa-padding-right"></i>
        Update User
    </button>

</form>

<script>

    async function validateMainForm() {
        const alphaNumeric = new RegExp(/[^a-zA-Z0-9]/g);
        
        let errors = 0;

        let first = document.getElementById("first");
        let first_error = document.getElementById("first-error");
        first_error.innerHTML = "";
        if (first.value == "") {
            first_error.innerHTML = "(first name is required)";
            errors++;
        }

        let last = document.getElementById("last");
        let last_error = document.getElementById("last-error");
        last_error.innerHTML = "";
        if (last.value == "") {
            last_error.innerHTML = "(last name is required)";
            errors++;
        }

        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        } else if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            email_error.innerHTML = "(valid email is required)";
            errors++;
        }

        let url = document.getElementById("url");
        let url_error = document.getElementById("url-error");
        url_error.innerHTML = "";
        if (url.value != "")
        {
            if (url.value.length < 3) 
            {
                url_error.innerHTML = "(URL must be at least 3 characters)";
                errors++;
            }
            else if (alphaNumeric.test(url.value)) 
            {
                url_error.innerHTML = "(URL may only contain letters and numbers)";
                errors++;
            } 
            else 
            {
                const json = await validateExistingUrl(url.value);
                if(json.error == true)
                {
                    url_error.innerHTML = "(URL already exists)";
                    errors ++;
                }
            }
        }

        if (errors) return false;

        let mainForm = document.getElementById('main-form');
        mainForm.submit();
    }

    async function validateExistingUrl(url) {
        return fetch('/ajax/url/exists',{
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({url: url, id: <?=$record['id']?>})
            })  
            .then((response)=>response.json())
            .then((responseJson)=>{return responseJson});
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/html_footer.php');

?>
