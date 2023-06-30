// AJAX: upvote, downvote
function projectVotes(user_id, project_id, stat, obj) {
  const clickedSum = obj.closest(".card").querySelector(".sum");
  const xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    `index.php?action=getProjectVotes&user_id=${user_id}&project_id=${project_id}&stat=${stat}`
  );

  xhr.addEventListener("load", () => {
    // clickedSum.innerHTML = xhr.responseText;
    // Assuming the server returns JSON data as an array
    const votes = JSON.parse(xhr.response);

    // Update the vote count
    clickedSum.innerHTML = votes.sum;

    const upvoteButton = obj.closest(".card").querySelector("#upVote");
    const p = obj.closest(".card").querySelector(".sum");

    if (votes.stat == 1) {
      // Highlight upvote button
      upvoteButton.classList.add("upVoted");
      p.classList.add("upVoted");
      // Remove highlight from downvote button
    } else if (votes.stat == 0) {
      // Remove highlight from both buttons
      upvoteButton.classList.remove("upVoted");
      p.classList.remove("upVoted");
    }
  });
  xhr.send();
}
