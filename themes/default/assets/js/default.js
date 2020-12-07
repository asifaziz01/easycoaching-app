const appPath = 'https://easycoachingapp.com/app/'; 
const logoutPath = 'login/login_actions/logout';
const updatePath = 'login/login_actions/update_session';
const applicationServerPublicKey = 'BBpQAy6d2Q1LKgwAqLU96oHM1Ugyvyq8eDiPlyptO40juyjFQV9wgC6Sem2VcjmuFKY081z30DwLYpz3kF9YA8A';
const sidebarSection = document.getElementById("sidebar");
const mainSection = document.getElementById("content");
const outputDiv = document.getElementById("response"); 
const loader = document.getElementById("loader");
const notifyButton = document.querySelector('.enable-notification');

let isSubscribed = false;
let swRegistration = null;