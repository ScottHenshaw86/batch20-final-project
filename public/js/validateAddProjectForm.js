/*
************
************
Query Elements
************
************
*/
const tagContainer = document.querySelector(".tag-container");
const tagInput = document.querySelector(".tag-container input");

const languageContainer = document.querySelector(".languages-container");
const languageInput = document.querySelector(".languages-container input");

const form = document.querySelector("form");

var tags = []; // store all tags

var languages = []; // store all languages

if (languageInput.value) {
    languages = languageInput.value.split(",");
    addLanguages();
    languageInput.value = "";
}

if (tagInput.value) {
    tags = tagInput.value.split(",");
    addTags();
    tagInput.value = "";
}

/*
************
************
Functions
************
************
*/

/**
 * Validates Project URL
 *
 * @param {string} url The url to validate.
 */
function validateURL(url) {
    try {
        new URL(url);
        gif.className = "purple";
        return url.length < 255 ? true : false;
        // return true;
    } catch {
        gif.className = "red";
        return false;
    }
}

/**
 * Validates Project Title
 *
 * @param {string}txt The title to validate.
 */
function validateTitle(txt) {
    if (txt.length < 3) {
        title.className = "red";
        return false;
    } else {
        title.className = "purple";
        return true;
    }
}

/**
 * Validates Project Description
 *
 * @param {string} txt The description to validate.
 */
function validateDesc(txt) {
    if (txt.length < 5) {
        description.className = "red";
        return false;
    } else {
        description.className = "purple";
        return true;
    }
}

/**
 * Creates Tag and Appends to Container
 *
 * @param {string} label The text to go into tag
 */
function createTag(label) {
    const div = document.createElement("div");
    div.className = "tag";
    const span = document.createElement("span");
    span.textContent = label;
    const closeBtn = document.createElement("i");
    closeBtn.className = "fa-xmark fa-solid";
    closeBtn.setAttribute("data-item", label);

    div.appendChild(span);
    div.appendChild(closeBtn);
    return div;
}

/**
 * Resets all tags in tag-container
 */
function resetTags() {
    tagContainer.querySelectorAll(".tag").forEach((tag) => {
        tag.parentElement.removeChild(tag);
    });
}

/**
 * Add tags to tag-container
 */
function addTags() {
    resetTags();
    tags.slice()
        .reverse()
        .forEach((tag) => {
            const input = createTag(tag);
            tagContainer.prepend(input);
        });
}

/**
 * Add languages to language-container
 */
function addLanguages() {
    resetLanguages();
    languages
        .slice()
        .reverse()
        .forEach((language) => {
            const input = createTag(language);
            languageContainer.prepend(input);
        });
}

/**
 * Resets all language tags in language-container
 */
function resetLanguages() {
    languageContainer.querySelectorAll(".tag").forEach((language) => {
        language.parentElement.removeChild(language);
    });
}

/**
 * Gets languages that start with user input
 *
 * @param {string} txt The text that languages must start with
 */
function getLanguages(txt) {
    if (!txt) {
        languageResults.className = "";
        languageResults.innerHTML = "";
        return;
    }

    const xhr = new XMLHttpRequest();

    xhr.open(
        "GET",
        "https://gist.githubusercontent.com/calvinfroedge/defeb8fc6cdc0068e172/raw/7904b2504827f6f4883df0299a2bf51accbe9dab/languages.json"
    );

    xhr.addEventListener("load", () => {
        languageResults.innerHTML = ""; // clear previous results
        const response = xhr.responseText.split("");
        response.pop();
        response.pop();
        response.shift();
        response.shift();

        // dataArr is array of programming languages
        const dataArr = response.join("").replaceAll('"', "").split(",");

        languageResults.className = "border";
        dataArr.forEach((language) => {
            if (language.toLowerCase().startsWith(txt.toLowerCase(), 0)) {
                const p = document.createElement("p");
                p.textContent = language;
                // add click event listener
                p.addEventListener('click', () => {
                    languages.push(p.textContent);
                    addLanguages();
                    languageInput.value = "";
                    getLanguages('');
                    validateLanguages();
                })
                languageResults.appendChild(p);
            }
        });

        if (!languageResults.hasChildNodes()) {
            languageResults.className = "";
            languageResults.innerHTML = "";
        }
    });

    xhr.send(null);
}

function validateTags() {
    if (
        tags.length === 0 ||
        tags.length > 5 ||
        tags.length !== new Set(tags).size
    ) {
        tagContainer.style.border = "1px solid red";
        return false;
    } else {
        tagContainer.style.border = "3px solid #8d5bf8";
        return true;
    }
}

function validateLanguages() {
    if (
        languages.length === 0 ||
        languages.length > 8 ||
        languages.length !== new Set(languages).size
    ) {
        languageContainer.style.border = "1px solid red";
        return false;
    } else {
        languageContainer.style.border = "3px solid #8d5bf8";
        return true;
    }
}

/*
************
************
Event Listeners
************
************
*/

title.addEventListener("keyup", () => {
    validateTitle(title.value);
});

description.addEventListener("keyup", (e) => {
    validateDesc(description.value);
});

gif.addEventListener("keyup", () => {
    validateURL(gif.value);
});

tagInput.addEventListener("keyup", (e) => {
    if (e.key === "Enter") {
        if (tagInput.value.length < 20) {
            tags.push(tagInput.value);
            addTags();
            tagInput.value = "";
            validateTags();
        } else {
            tagContainer.style.border = "1px solid red";
        }
    }
});

// validate number of tags user can input + no duplicate tags
// tagInput.addEventListener("keyup", validateTags);

tagContainer.addEventListener("click", (e) => {
    if (e.target.tagName === "I") {
        const value = e.target.getAttribute("data-item");
        const index = tags.indexOf(value);
        tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
        addTags();
        validateTags();
    }
});

// prevent user from submitting form on 'enter'
form.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
    }
});

let allLanguages; // list of all languages that start with text in 'languagesInput'
let language; // language user currently selected in #languageResults
let i; // index of currently selected language

languagesInput.addEventListener("keyup", function (e) {
    allLanguages = languageResults.querySelectorAll("p");
    if (e.key === "Enter") {
        // language && allLanguages.length
        //     ? languages.push(language.textContent)
        //     : languages.push(languageInput.value);
        if (language) {
            languages.push(language.textContent);
        } else {
            if (languagesInput.value.length < 20) {
                languages.push(languagesInput.value);
            } else {
                languageContainer.style.border = "1px solid red";
                return;
            }
        }
        language = null;
        validateLanguages();
        addLanguages();
        languageInput.value = "";
        getLanguages("");
    } else if (e.key === "ArrowDown") {
        if (i >= 0 && i <= allLanguages.length - 1) {
            allLanguages[i].className = "";
        }
        if (i === allLanguages.length - 1) {
            language = allLanguages[0];
            i = 0;
            language.classList.add("selectSearch");
        } else if (i < allLanguages.length - 1) {
            language = allLanguages[++i];
            language.classList.add("selectSearch");
            // console.log(language);
        }
    } else if (e.key === "ArrowUp") {
        if (i > 0) {
            language.classList.remove("selectSearch");
            language = allLanguages[--i];
            language.classList.add("selectSearch");
            // console.log(language);
        } else if (i === 0 && Boolean(language)) {
            language.classList.remove("selectSearch");
            i = allLanguages.length - 1;
            language = allLanguages[i];
            language.classList.add("selectSearch");
        }
    } else {
        i = -1;
        getLanguages(languagesInput.value);
    }
});

// user can remove tag/language on container if they click on delete button
languageContainer.addEventListener("click", (e) => {
    if (e.target.tagName === "I") {
        const value = e.target.getAttribute("data-item");
        const index = languages.indexOf(value);
        languages = [
            ...languages.slice(0, index),
            ...languages.slice(index + 1),
        ];
        addLanguages();
        validateLanguages();
    }
});


// Before submitting form, join the 'tags' and 'languages' array and set them as values to their respective input field
form.addEventListener("submit", (e) => {
    let valid = validateURL(gif.value);
    valid = validateTitle(title.value) && valid;
    valid = validateTags() && valid;
    valid = validateDesc(description.value) && valid;
    valid = validateLanguages() && valid;

    if (valid) {
        alert("Valid");
        // Set the value of the languagesInput input field
        languagesInput.value = languages.join(",");
        // Set the value of the tags input field
        tagInput.value = tags.join(",");
    } else {
        alert("Not valid");
        e.preventDefault();
    }
});



// validate number of languages user can input + no duplicate languages
// languageInput.addEventListener("keyup", validateLanguages);
