<?php

require_once "Manager.php";
class ProjectManager extends Manager
{
    public function getCards()
    {
        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.gif, p.description, l.language_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id;";

        $res = $db->query($sql);

        $projects = [];
        while ($data = $res->fetch()) {
            $project_id = $data->id;
            if (isset($projects[$project_id])) {
                $projects[$project_id]->languages[] = $data->language_name;
            } else {
                $projects[$project_id] = $data;
                $projects[$project_id]->languages = [];
                $projects[$project_id]->languages[] = $data->language_name;

                $sumsQuery = $db->prepare("SELECT SUM(stat) AS sum_stat FROM project_votes WHERE project_id = :project_id");
                $sumsQuery->bindParam("project_id", $project_id, PDO::PARAM_INT);
                $sumsQuery->execute();
                $sum = $sumsQuery->fetch()->sum_stat;
                $projects[$project_id]->sum = $sum;

                unset($projects[$project_id]->language_name);
            }
        }
    }

    public function getUserProjects($user_id)
    {
        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.gif, p.description, l.language_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id
            WHERE u.id = ?";

        $res = $db->prepare($sql);
        $res->execute([$user_id]);


        $projects = [];
        while ($data = $res->fetch()) {
            $project_id = $data->id;
            if (isset($projects[$project_id])) {
                $projects[$project_id]->languages[] = $data->language_name;
            } else {
                // first time checking
                $projects[$project_id] = $data;
                $projects[$project_id]->languages = [];
                $projects[$project_id]->languages[] = $data->language_name;

                unset($projects[$project_id]->language_name);
            }
        }
        return $projects;
    }

    public function projectVotes($user_id, $project_id, $stat)
    {
        $db = $this->dbConnect();

        $sql = "SELECT u.id, p.id, pv.user_id, pv.project_id, pv.stat
                FROM project p 
                INNER JOIN project_votes pv
                ON pv.project_id = :project_id
                INNER JOIN user u 
                ON pv.user_id = :user_id";

        $req = $db->prepare($sql);
        $req->execute(array(
            "project_id" => $project_id,
            "user_id" => $user_id
        ));
        $data = $req->fetch();

        if ($data->user_id == 0) {
            echo "oh no";
        } else {
            if ($data->stat != 0) {
                $req = $db->prepare("UPDATE project_votes SET stat = 0 WHERE user_id = :user_id and project_id = :project_id");
                $req->bindParam("user_id", $user_id, PDO::PARAM_INT);
                $req->bindParam("project_id", $project_id, PDO::PARAM_INT);
                $req->execute();
            } else {
                if ($data) {
                    // run an UPDATE
                    $req = $db->prepare("UPDATE project_votes SET stat = :stat WHERE user_id = :user_id and project_id = :project_id");
                    $req->bindParam("stat", $stat, PDO::PARAM_INT);
                    $req->bindParam("user_id", $user_id, PDO::PARAM_INT);
                    $req->bindParam("project_id", $project_id, PDO::PARAM_INT);
                    $req->execute();
                } else {
                    // do an INSERT
                    $req = $db->prepare("INSERT INTO project_votes (user_id, project_id, stat) VALUES (:user_id, :project_id, :stat)");
                    $req->bindParam("user_id", $user_id, PDO::PARAM_INT);
                    $req->bindParam("project_id", $project_id, PDO::PARAM_INT);
                    $req->bindParam("stat", $stat, PDO::PARAM_INT);
                    $req->execute();
                }
            }
        }
    }
}
