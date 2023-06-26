function projectVotes(user_id, project_id, stat) {
  window.location.href = `index.php?action=getUserProjectVotes&user_id=${user_id}&project_id=${project_id}&stat=${stat}`;
}
