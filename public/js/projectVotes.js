// // AJAX: upvote, downvote
// function projectVotes(user_id, project_id, stat) {
//   window.location.href = `index.php?action=getProjectVotes&user_id=${user_id}&project_id=${project_id}&stat=${stat}`;
// }

function projectVotes(user_id, project_id, stat) {
  // window.location.href = `index.php?action=getProjectVotes&user_id=${user_id}&project_id=${project_id}&stat=${stat}`;
  const xhr = new XMLHttpRequest();

  xhr.open(
    "POST",
    `index.php?action=getProjectVotes&user_id=${user_id}&project_id=${project_id}&stat=${stat}`
  );
  xhr.addEventListener("load", (e) => {
    console.log(xhr.responsetext);
    console.log(e.currentTarget);
  });

  xhr.send(null);
}
