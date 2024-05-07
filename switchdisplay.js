const btn1 = document.getElementById("btn1");
const btn2 = document.getElementById("btn2");
const btn3 = document.getElementById("btn3");
const section1 = document.getElementById("section1");
const section2 = document.getElementById("section2");
const section3 = document.getElementById("section3");

function swapdescription(){
	var fill = document.getElementById('infoBox');
	var fillTo = document.getElementById("description").value;
	fill.textContent = fillTo;
	//document.getElementById("description").value = "Enter description here...";
	alert("Changes Saved.")
}
function swapdob(){
	var fill = document.getElementById('DOB');
	var fillTo = document.getElementById("date").value;
	fill.textContent = fillTo;
	alert("Changes Saved.")
}
function swapmail(){
	var fill = document.getElementById('email1');
	var fillTo = document.getElementById("email").value;
	fill.textContent = fillTo;
	alert("Changes Saved.")
}

function plop(){
	document.getElementById("profilePic").src = "bored.gif";
}

function showSection(section) {
        section.style.display = "block";
}

function copyToBox(){
	var fill = document.getElementById('infoBox').textContent;
	document.getElementById("description").value = fill;
}


function swaptheme(){
	var sheets = document.getElementsByTagName('link')[0]; 
	if (theme.getAttribute('href') == 'ElderPageStylesheet.css'){
		theme.setAttribute('href', 'mono.css');
	}else{
		theme.setAttribute('href', 'ElderPageStylesheet.css');
	}
}
function swapbold(){
	var sheets = document.getElementsByTagName('link')[0]; 
	if (themeb.getAttribute('href') == 'undo.css'){
		themeb.setAttribute('href', 'bold.css');
	}else{
		themeb.setAttribute('href', 'undo.css');
	}
}
function swaplarge(){
	var sheets = document.getElementsByTagName('link')[0]; 
	if (themel.getAttribute('href') == 'undo.css'){
		themel.setAttribute('href', 'large.css');
	}else{
		themel.setAttribute('href', 'undo.css');
	}
}


function hideSection() {
	section1.style.display = "none";
        section2.style.display = "none";
	section3.style.display = "none";
	section4.style.display = "none";
	section5.style.display = "none";
	section6.style.display = "none";
}

function load() {
	section1.style.display = "block";
        section2.style.display = "block";
	section3.style.display = "none";
	section4.style.display = "none";
	section5.style.display = "none";
	section6.style.display = "none";
}

function highlight(button) {
	btn1.classList.remove("active");
	btn2.classList.remove("active");
	btn3.classList.remove("active");
	btn4.classList.remove("active");
	button.classList.add("active");
}

btn1.addEventListener("click", () => {
	hideSection();
        showSection(section1);
	showSection(section2);
	highlight(btn1);
});
btn2.addEventListener("click", () => {
	hideSection();
        showSection(section2);
	highlight(btn2);
});
btn3.addEventListener("click", () => {
	hideSection();
        showSection(section3);
	showSection(section4);
	copyToBox();
	highlight(btn3);
});
btn4.addEventListener("click", () => {
	hideSection();
	showSection(section5);
	showSection(section6);
	highlight(btn4);
});