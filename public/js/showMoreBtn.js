offset = 0; // offset for default
mostLikesLimit = 4;
mostRecentLimit = 4;


// filterProjects(filterOn) is called whenever user changes value of dropdown list and value of selected val is passed as argument
function filterProjects(filterOn) {
    currSelected = document.querySelector(".selected");

    currSelected.classList.remove("selected");

    const options = filter.querySelectorAll("option");
    
    // change the 'selected' class to currently selected filter
    options.forEach((option) => {
        if (option.value == filterOn) {
            option.classList.add("selected");
            console.log(option);
        }
    }); 

    
    // if the filterOn is 'mostLikes' use the mostLikesLimit, else the only other option is to use 'mostRecentLikes'
    limit = filterOn == "mostLikes" ? mostLikesLimit : mostRecentLimit;

    const xhr = new XMLHttpRequest();

    xhr.open("GET", `index.php?action=filter&filterOn=${filterOn}&limit=${limit}`);
    
    xhr.addEventListener("load", () => {
        const projectContainer = document.querySelector(".project-container");

        // from projectController.php -> getFilteredProjects function, response text is HTML
        const response = xhr.responseText;
        projectContainer.innerHTML = response;
    });

    xhr.send();
}

// show more button
showMoreBtn = document.querySelector(".showMoreBtn");

showMoreBtn.addEventListener("click", () => {
    const currSelectedFilter = filter.querySelector(".selected");
    const xhr = new XMLHttpRequest();

    // filter on default
    if (currSelectedFilter.value == "default") {
        offset += 4;
    
        xhr.open("GET", `index.php?offset=${offset}`);
    
        xhr.addEventListener("load", () => {
            // clear previous projects
            const projectContainer = document.querySelector(".project-container");
    
            // from projectController.php -> getFilteredProjects function, response text is HTML
            const response = xhr.responseText;
            projectContainer.innerHTML += response;
        })
    }


    // filter on mostLikes: use limit instead of offset
    else if (currSelectedFilter.value == "mostLikes") {
        mostLikesLimit += 4;

        xhr.open("GET", `index.php?action=filter&filterOn=mostLikes&limit=${mostLikesLimit}`);
    
        xhr.addEventListener("load", () => {
            // clear previous projects
            const projectContainer = document.querySelector(".project-container");
    
            // from projectController.php -> getFilteredProjects function, response text is HTML
            const response = xhr.responseText;
            projectContainer.innerHTML = response;
        })
        

    // filter on mostRecent (also use a limit instead of offset)
    } else {
        mostRecentLimit += 4;

        xhr.open(
            "GET",
            `index.php?action=filter&filterOn=mostRecent&limit=${mostRecentLimit}`
        );

        xhr.addEventListener("load", () => {
            // clear previous projects
            const projectContainer =
                document.querySelector(".project-container");

            // from projectController.php -> getFilteredProjects function, response text is HTML
            const response = xhr.responseText;
            projectContainer.innerHTML = response;
        });

    }
    
    
    xhr.send()
})