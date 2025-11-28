<?php

security_check();
admin_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
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
            validate_url_exists($_POST['url'], 'users'))
        {
            message_set('URL Error', 'There was an error with the provided URL.', 'red');
            header_redirect('/admin/dashboard');
        }
    }

    // Check if email already exists
    $query = 'SELECT id 
        FROM users 
        WHERE email = "'.addslashes($_POST['email']).'" 
        LIMIT 1';
    $result = mysqli_query($connect, $query);
    
    if (mysqli_num_rows($result) > 0) 
    {
        message_set('User Error', 'This email already exists in the system.', 'red');
        header_redirect('/admin/dashboard');
    }

    // Save user details to the database
    $query = 'INSERT INTO users (
            first, 
            last, 
            email,
            url,
            created_at,
            updated_at
        ) VALUES (
            "'.addslashes($_POST['first']).'",
            "'.addslashes($_POST['last']).'",
            "'.addslashes($_POST['email']).'",
            "'.addslashes($_POST['url']).'",
            NOW(),
            NOW()
        )';
    mysqli_query($connect, $query);

    message_set('User Success', 'User has been successfully added.');
    header_redirect('/admin/dashboard');

}

define('APP_NAME', 'My Account');
define('PAGE_TITLE', 'Add User');
define('PAGE_SELECTED_SECTION', 'users');
define('PAGE_SELECTED_SUB_PAGE', '/admin/add');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

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
    Add User
</p>

<hr>

<h2>Add User</h2>

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
    />
    <label for="url" class="w3-text-gray">
        URL <span id="url-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="validateMainForm(); return false;">
        <i class="fa-solid fa-plus fa-padding-right"></i>
        Add User
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
                body: JSON.stringify({url: url})
            })  
            .then((response)=>response.json())
            .then((responseJson)=>{return responseJson});
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/html_footer.php');

