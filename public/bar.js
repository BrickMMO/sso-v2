
const script = document.currentScript;
const site = script.dataset.site;
const local = script.dataset.local == "true" ? true : false;



let topbarHtml = `
<div id="bar-container">
  <a href="${site}" id="bar-brickmmo"><img src="https://cdn.brickmmo.com/images@1.0.0/brickmmo-logo-coloured-horizontal.png" /></a>
  <a href="${site}/admin" id="bar-console"><img src="https://cdn.brickmmo.com/images@1.0.0/navbar-console.png" /></a>
  <a href="https://assets.brickmmo.com/" id="bar-hamburger"><img src="https://cdn.brickmmo.com/images@1.0.0/navbar-assets.png" /></a>
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
  #bar-container a#bar-brickmmo {
    left: 20px;
  }
  #bar-container a#bar-console {
    right: 70px;
  }
  #bar-container a#bar-hamburger {
    right: 20px;
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
