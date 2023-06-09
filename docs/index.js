window.addEventListener("DOMContentLoaded", () => {
	const nav = document.querySelector(".table-of-content");
	const titles = document.querySelectorAll(".title");
	let activeNavIndex = -1;

	const observer = new IntersectionObserver((entries) => {
		entries.forEach((entry, index) => {
			const id = entry.target.getAttribute("id");
			const navLink = nav.querySelector(`a[href="#${id}"]`);

			if (entry.isIntersecting && index !== activeNavIndex) {
				nav.querySelectorAll("a").forEach((link) => link.classList.remove("active"));
				navLink.classList.add("active");
				activeNavIndex = index;
			}
		});
	});

	titles.forEach((title, index) => {
		observer.observe(title);

		if (title.getBoundingClientRect().top < window.innerHeight / 2 && activeNavIndex === -1) {
			activeNavIndex = index;
			nav.querySelector(`a[href="#${title.id}"]`).classList.add("active");
		}
	});

	window.addEventListener("scroll", () => {
		const scrollPosition = window.scrollY;

		if (scrollPosition === 0 && activeNavIndex !== -1) {
			nav.querySelectorAll("a").forEach((link) => link.classList.remove("active"));
			activeNavIndex = -1;
		}
	});

	const currentYear = document.getElementById('current-year');
	if (currentYear) {
		currentYear.textContent = new Date().getFullYear();
	}
});