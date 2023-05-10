

function toggleSideMenu() {
	// document.querySelector(" .hamburger").classList.toggle('active');
	document.body.classList.toggle("sideMenuOpen"); // //
	//    document.getElementById('aside').style.width = '250px'; // document.getElementById('main').style.marginLeft='250px' ;
}
window.onresize = () => {
	if (document.body.classList.contains("sideMenuOpen")) toggleSideMenu();
};

var script = document.currentScript;
var fullUrl = script.src;

window.onload = () => {
}
