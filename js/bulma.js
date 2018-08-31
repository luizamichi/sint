// MENU RESPONSIVO
document.addEventListener("DOMContentLoaded", () => {
	const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll(".navbar-burger"), 0);
	if ($navbarBurgers.length > 0) {
		$navbarBurgers.forEach(el => {
			el.addEventListener("click", () => {
				const target = el.dataset.target;
				const $target = document.getElementById(target);
				el.classList.toggle("is-active");
				$target.classList.toggle("is-active");
			});
		});
	}
});

// BANNER
let slideIndex = 0;
autoShow();

function autoShow() {
	let i;
	let slides = document.getElementsByClassName("slide");
	let dots = document.getElementsByClassName("dot");
	if (slides.length == 0)
		return;
	for (i = 0; i < slides.length; i++)
		slides[i].style.display = "none";
	slideIndex++;
	if (slideIndex > slides.length)
		slideIndex = 1;
	for (i = 0; i < dots.length; i++)
		dots[i].className = dots[i].className.replace(" active", "");
	slides[slideIndex - 1].style.display = "block";
	dots[slideIndex - 1].className += " active";
	setTimeout(autoShow, 5000);
}

function plusSlides(n) {
	showSlides(slideIndex += n);
}

function currentSlide(n) {
	showSlides(slideIndex = n);
}

function showSlides(n) {
	let i;
	let slides = document.getElementsByClassName("slide");
	let dots = document.getElementsByClassName("dot");
	if (n > slides.length)
		slideIndex = 1;
	if (n < 1)
		slideIndex = slides.length;
	for (i = 0; i < slides.length; i++)
		slides[i].style.display = "none";
	for (i = 0; i < dots.length; i++)
		dots[i].className = dots[i].className.replace(" active", "");
	slides[slideIndex - 1].style.display = "block";
	dots[slideIndex - 1].className += " active";
}