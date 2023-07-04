let circularProgress = document.querySelectorAll(".circular-progress"),
    progressValue = document.querySelectorAll(".progress-value");

let speed = 50;
let i = 0;

// Fade on scroll
const observer = new IntersectionObserver(
    (entries) => {
        if (entries[i].isIntersecting) {
            for (let i = 0; i < 3; i++) {
                let progressStartValue = 0;
                let progressEndValue = progressValue[i].textContent;
                let progress = setInterval(() => {
                    progressStartValue++;
                    progressValue.textContent = `${progressStartValue}`;
                    circularProgress[
                        i
                    ].style.background = `conic-gradient(#7d2ae8 ${
                        progressStartValue * 3.6
                    }deg, #ededed 0deg)`;
                    if (progressStartValue == progressEndValue) {
                        clearInterval(progress);
                    }
                }, speed);
            }
        }
    },
    {
        threshold: 1,
    }
);

circularProgress.forEach((stat) => {
    observer.observe(stat);
});
