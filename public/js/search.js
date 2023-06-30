const projectContainer = document.querySelector(".project-container");
if (projectContainer) {
	input.addEventListener("keyup", function (e) {
		if (input.value && e.key === "Enter") {
			// step 1
			const xhr = new XMLHttpRequest();

			// step 2
			xhr.open(
				"GET",
				`http://localhost/sites/batch20-final-project/index.php?action=search_data&query=${input.value}`
			);

			// step 4
			xhr.addEventListener("load", () => {
				// const projectContainer = document.querySelector(".project-container");
				projectContainer.innerHTML = xhr.responseText;
			});
			// step 3
			xhr.send(null);
		}
	});
}

const userProfileProjects = document.querySelector(".projects");
if (userProfileProjects) {
	input.addEventListener("keyup", function (e) {
		if (input.value && e.key === "Enter") {
			// step 1
			const xhr = new XMLHttpRequest();

			// step 2
			xhr.open(
				"GET",
				`http://localhost/sites/batch20-final-project/index.php?action=search_data&query=${input.value}`
			);

			// step 4
			xhr.addEventListener("load", () => {
				// const userProfileProjects = document.querySelector(".projects");
				userProfileProjects.innerHTML = xhr.responseText;
			});
			// step 3
			xhr.send(null);
		}
	});
}
