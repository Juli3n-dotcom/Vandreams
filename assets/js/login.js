var login = document.getElementById('login');
var register = document.getElementById('register');
var btn = document.getElementById('btn');

document.getElementById('register_btn').addEventListener('click', function(){
login.style.left = "-400px";
register.style.left = "40px";
btn.style.left = "120px";
// login.style.display="none";
// register.style.display="block";
})

document.getElementById('login_btn').addEventListener('click', function(){
login.style.left = "40px";
register.style.left = "450px";
btn.style.left = "0";
// login.style.display="block";
// register.style.display="none";
})