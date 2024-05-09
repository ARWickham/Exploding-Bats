const btn1 = document.getElementById("btn1");
const btn2 = document.getElementById("btn2");
const btn3 = document.getElementById("btn3");
const section1 = document.getElementById("section1");
const section2 = document.getElementById("section2");
const section3 = document.getElementById("section3");

function swapdescription(){
	//used to change the value in the text field to the box
	//saves the two values as variables
	var fill = document.getElementById('infoBox');
	var fillTo = document.getElementById("description").value;
	//transferrs one to the other
	fill.textContent = fillTo;
	//document.getElementById("description").value = "Enter description here...";
	alert("Changes Saved.")
}
function swapdob(){
	//same concept as above
	var fill = document.getElementById('DOB');
	var fillTo = document.getElementById("date").value;
	fill.textContent = fillTo;
	alert("Changes Saved.")
}
function swapmail(){
	//same concept as above
	var fill = document.getElementById('email1');
	var fillTo = document.getElementById("email").value;
	fill.textContent = fillTo;
	alert("Changes Saved.")
}

function plop(){
	//changes the profile picture
	document.getElementById("profilePic").src = "bored.gif";
}

function showSection(section) {
	//activates one of the sections on the page
        section.style.display = "block";
}

function copyToBox(){
	//same as top
	var fill = document.getElementById('infoBox').textContent;
	document.getElementById("description").value = fill;
}


function swaptheme(){
	//changes the default stylesheet to the black and white one
	var sheets = document.getElementsByTagName('link')[0]; 
	//because a toggle is used an if statement is used to see which is currently in use
	if (theme.getAttribute('href') == 'ElderPageStylesheet.css'){
		theme.setAttribute('href', 'mono.css');
	}else{
		theme.setAttribute('href', 'ElderPageStylesheet.css');
	}
}
function swapbold(){
	//same as above
	var sheets = document.getElementsByTagName('link')[0]; 
	if (themeb.getAttribute('href') == 'undo.css'){
		themeb.setAttribute('href', 'bold.css');
	}else{
		themeb.setAttribute('href', 'undo.css');
	}
}
function swaplarge(){
	//same as above
	var sheets = document.getElementsByTagName('link')[0]; 
	if (themel.getAttribute('href') == 'undo.css'){
		themel.setAttribute('href', 'large.css');
	}else{
		themel.setAttribute('href', 'undo.css');
	}
}


function hideSection() {
	//hides all sections
	section1.style.display = "none";
        section2.style.display = "none";
	section3.style.display = "none";
	section4.style.display = "none";
	section5.style.display = "none";
	section6.style.display = "none";
}

function load() {
	//opens section 1 and 2 on load
	section1.style.display = "block";
        section2.style.display = "block";
	section3.style.display = "none";
	section4.style.display = "none";
	section5.style.display = "none";
	section6.style.display = "none";
}

function highlight(button) {
	//used to set which button is active in nav bar
	btn1.classList.remove("active");
	btn2.classList.remove("active");
	btn3.classList.remove("active");
	btn4.classList.remove("active");
	button.classList.add("active");
}


//all of below do the same thing just with different buttons
btn1.addEventListener("click", () => {
	//starts by hiding all sections
	hideSection();
	//show sections 1 and 2
        showSection(section1);
	showSection(section2);
	//sets button one as active (red)
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