const script = document.currentScript;

// Does this app have an admin
const cons = script.dataset.console == "true" ? true : false;
const menu = script.dataset.menu == "true" ? true : false;
const admin = script.dataset.admin == "true" ? true : false;
const local = script.dataset.local == "true" ? true : false;
const https = script.dataset.https == "true" ? true : false;

// console.log(cons);
// console.log(menu);
// console.log(local);
// console.log(https);

const domain = window.location.host;
const site = (https ? 'https' : 'http' ) + '://' + domain;

const sso = (https ? 'https' : 'http' ) + '://' + (local ? 'local.' : '') + 'sso.brickmmo.com' + (local ? ':7777' : '');
const profile = domain.includes('sso.brickmmo');

let topbarHtml = `
<div id="bar-container">

  <a href="javascript: return voi d(0);" id="bar-menu" onclick="w3SidebarToggle(event)">
    <img src="https://cdn.brickmmo.com/images@1.0.0/navbar-menu.png" />
  </a> 
  <a href="${site}" id="bar-brickmmo">
    <img src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png" />
  </a>

  <a href="${site}/admin/dashboard" id="bar-admin">
    <img src="https://cdn.brickmmo.com/images@1.0.0/navbar-admin.png" />
  </a>
  <a href="${site}/console/dashboard" id="bar-console">
    <img src="https://cdn.brickmmo.com/images@1.0.0/navbar-console.png" />
  </a>
  <a href="${sso}" id="bar-user">
    <img src="https://cdn.brickmmo.com/images@1.0.0/no_avatar.png">
  </a>
  <a href="https://assets.brickmmo.com/" id="bar-hamburger">
    <img src="https://cdn.brickmmo.com/images@1.0.0/navbar-assets.png" />
  </a> 

</div>

<style>
  #bar-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 58px;
    background: #fff;
    color: white;
    display: flex;
    align-items: center;
    padding: 0 10px;
    z-index: 9999;
    box-sizing: border-box;
    border-bottom: 1px solid #ccc;
  }
  #bar-container a {
    display: block;
    position: absolute;
    height: 35px;
  }
  #bar-container a:link,
  #bar-container a:active,
  #bar-container a:visited,
  #bar-container a:hover {
    color: #ff5b00 !important;
    text-decoration: none !important;
    cursor: pointer;
  }

  #bar-container a#bar-menu {
    display: none;
    left: 20px;
  }
  #bar-container a#bar-brickmmo {
    left: 20px;
  }

  #bar-container a#bar-admin {
    display: none;
    right: 150px;
  }
  #bar-container a#bar-console {
    display: none;
    right: 110px;
  }
  #bar-container a#bar-user {
    right: 70px;
  }
  #bar-container a#bar-hamburger {
    right: 20px;
  }

  #bar-container a#bar-user img {
    border-radius: 50%;
    height: 25px;
    margin-top: 5px;
  }
  #bar-container img {
    height: 35px;
  }
  body {
    margin-top: 58px !important;
  }
  .markdown-body h1:first-of-type {
    margin-top: 20px !important;
  }
</style>
`;

// Insert topbar at the top of the body
document.body.insertAdjacentHTML('afterbegin', topbarHtml);

// Adjust 100vh elements
document.querySelectorAll('*').forEach(el => {
  const style = window.getComputedStyle(el);
  if (style.height === window.innerHeight + 'px' || style.height === '100vh') {
    el.style.height = `calc(100vh - 58px)`;
  }
});

let barBrickMmo = document.getElementById('bar-brickmmo');
let barMenu = document.getElementById('bar-menu');

fetch(sso + '/api/user',{
  credentials: 'include',
})
.then(response => {
  return response.json();
})
.then(data => {
  if(data.error == false)
  {

    const barUser = document.getElementById('bar-user');
    const barConsole = document.getElementById('bar-console');
    const barAdmin = document.getElementById('bar-admin');

    // If there is an avatar
    if(data.user.avatar)
    {
      barUser.innerHTML = '';
      let avatar = document.createElement("img");
      avatar.src = data.user.avatar;
      barUser.appendChild(avatar);
    }

    // If the avatar should open a modal
    if(profile)
    {
      barUser.addEventListener("click", function(e){
        openModal('avatar-options');
        e.preventDefault();
      });
    }

    // If this app has a console
    if(cons)
    {
      barConsole.style.display = "block";
    }

    // If this app has an admin
    if(admin && data.user.admin == 1)
    {
      barAdmin.style.display = "block";

      if(!cons)
      {
        barAdmin.style.right = "110px";
      } 
    }
    

    // If this app has a slideout menu
    if(menu)
    {
      barBrickMmo.style.left = "70px";
      barMenu.style.display = "block";
    }
  }
});

