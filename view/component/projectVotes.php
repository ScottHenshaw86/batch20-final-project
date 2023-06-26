<!-- <input type="button" value="UPVOTE" id="upVote" name="upVote" onclick="console.log('hello')"> -->
<input type="button" value="UPVOTE" id="upVote" name="upVote" onclick="projectVotes(<?= isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?>, <?= $project->id ?>, '1');">
<input type="button" value="DOWNVOTE" id="downVote" name="downVote" onclick="projectVotes(<?= isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?>, <?= $project->id ?>, '-1');">
<p>SUM: <?= $project->sum ?></p>
<p><?= '<pre>';
    print_r($_SESSION);
    '</pre>'; ?> </p>
<?= $project->id ?>