<?php

require_once "Manager.php";
class ProjectManager extends Manager
{

    // public function getCards($offset)
    // {
    //     $limit = 4;

    //     $db = $this->dbConnect();
    //     $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name
    //         FROM user u
    //         INNER JOIN project p
    //         ON u.id = p.user_id
    //         INNER JOIN project_language_map plm
    //         ON p.id = plm.project_id
    //         INNER JOIN language l
    //         ON plm.language_id = l.id
    //         WHERE p.is_active = 1
    //         LIMIT :offset , 40";


    //     $res = $db->prepare($sql);
    //     $res->bindParam("offset", $offset, PDO::PARAM_INT);
    //     $res->execute();

    //     $projects = [];
    //     while ($data = $res->fetch()) {
    //         $project_id = $data->id;
    //         if (isset($projects[$project_id])) {
    //             $projects[$project_id]->languages[] = $data->language_name;
    //         } else if (count($projects) < $limit) {
    //             $projects[$project_id] = $data;
    //             $projects[$project_id]->languages = [];
    //             $projects[$project_id]->languages[] = $data->language_name;

    //             $sumsQuery = $db->prepare("SELECT SUM(stat) AS sum_stat FROM project_votes WHERE project_id = :project_id");
    //             $sumsQuery->bindParam("project_id", $project_id, PDO::PARAM_INT);
    //             $sumsQuery->execute();
    //             $sum = $sumsQuery->fetch()->sum_stat;
    //             $projects[$project_id]->sum = $sum;

    //             unset($projects[$project_id]->language_name);
    //         }
    //     }
    //     return $projects;
    // }
    public function getCards($limit)
    {

        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id
            WHERE p.is_active = 1";


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
        return array_slice($projects, 0, $limit);
    }

    public function getUserProjects($user_id)
    {
        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id
            WHERE u.id = ? and p.is_active = 1";

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

                $sumsQuery = $db->prepare("SELECT SUM(stat) AS sum_stat FROM project_votes WHERE project_id = :project_id");
                $sumsQuery->bindParam("project_id", $project_id, PDO::PARAM_INT);
                $sumsQuery->execute();
                $sum = $sumsQuery->fetch()->sum_stat;
                $projects[$project_id]->sum = $sum;

                unset($projects[$project_id]->language_name);
            }
        }
        return $projects;
    }

    public function displayFullProject($project_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare(
            "SELECT u.username, u.profile_img, u.linkedIn, u.gitHub, p.id, p.user_id as user_id, p.title, p.video_src, p.description, l.language_name
        FROM project p 
        INNER JOIN user u
            ON u.id = user_id
        INNER JOIN project_language_map plm
            ON p.id = plm.project_id
        INNER JOIN language l
            ON l.id = plm.language_id
        WHERE p.id = ?"
        );

        $req->bindParam("id", $project_id, PDO::PARAM_INT);

        $req->execute([$project_id]);

        $projects = [];
        while ($data = $req->fetch()) {
            $project_id = $data->id;
            if (isset($projects[$project_id])) {
                // this is NOT the 1st time we've seen this same project.
                // So just put the language name into the languages array
                $projects[$project_id]->languages[] = $data->language_name;
            } else {
                // This IS the 1st time we've seen this project id
                // so we need to add the project object to the $projects array
                // THEN we will create the languages array on that object
                // and put the language name into that array
                $projects[$project_id] = $data;
                $projects[$project_id]->languages = [];
                $projects[$project_id]->languages[] = $data->language_name;

                unset($projects[$project_id]->language_name);
            }
        }

        return $projects[$project_id];
    }

    // $project = $req->fetch();
    // return $project;
    public function projectVotes($user_id, $project_id, $stat) // UPVOTE -> 1 DOWNVOTE -> -1
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
        if ($data) {
            // NO like or dislike
            if ($stat == 1 and $data->stat == 0) {
                $req = $db->prepare("UPDATE project_votes SET stat = 1 WHERE user_id = :user_id and project_id = :project_id");
                $req->bindParam("user_id", $user_id, PDO::PARAM_INT);
                $req->bindParam("project_id", $project_id, PDO::PARAM_INT);
                $req->execute();
            } else if ($stat == 1 and $data->stat == 1) {
                $stat = 0;
                $req = $db->prepare("UPDATE project_votes SET stat = 0 WHERE user_id = :user_id and project_id = :project_id");
                $req->bindParam("user_id", $user_id, PDO::PARAM_INT);
                $req->bindParam("project_id", $project_id, PDO::PARAM_INT);
                $req->execute();
            }
        } else {
            // do an INSERT
            $req = $db->prepare("INSERT INTO project_votes (user_id, project_id, stat) VALUES (:user_id, :project_id, :stat)");
            $req->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $req->bindParam("project_id", $project_id, PDO::PARAM_INT);
            $req->bindParam("stat", $stat, PDO::PARAM_INT);
            $req->execute();
        }
        $sumsQuery = $db->prepare("SELECT SUM(stat) AS sum_stat FROM project_votes WHERE project_id = :project_id");
        $sumsQuery->bindParam("project_id", $project_id, PDO::PARAM_INT);
        $sumsQuery->execute();
        $sum = $sumsQuery->fetch()->sum_stat;


        $response = array(
            "sum" => $sum,
            "stat" => $stat
        );

        echo json_encode($response);
    }

    public function getUserVotes()
    {

        if (isset($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
        } else {
            return [];
        }
        $db = $this->dbConnect();

        $sql = $db->prepare(
            "SELECT stat, project_id FROM project_votes WHERE user_id = ?"
        );

        $sql->bindParam("id", $user_id, PDO::PARAM_INT);
        $sql->execute(
            [$user_id]
        );
        $votes = $sql->fetchAll();

        return $votes;
    }

    // INSERT NEW PROJECT
    public function insertNewProject($user_id, $video_source, $title, $description, $tags, $languages)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("INSERT INTO project (user_id, video_src, title, description)  VALUES (:user_id, :video_src, :title, :description)"); //TODO:insert into tag table and language table)
        $req->bindParam("video_src", $video_source, PDO::PARAM_STR);
        $req->bindParam("title", $title, PDO::PARAM_STR);
        $req->bindParam("description", $description, PDO::PARAM_STR);
        $req->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $req->execute();

        // TODO: Find out how to get the id of a row that was just inserted
        // $selectReq = $db->query(" id FROM project ORDER BY id DESC LIMIT 1");
        $project_id = $db->lastInsertId();

        // echo "LAST ID: " . $last_id . "<br><br><br><br><br>";

        $languageArray = explode(",", $languages);
        foreach ($languageArray as $language) {
            // $selectReq = $db->prepare("SELECT language_name FROM language WHERE language_name = ?");
            // $selectReq->execute([$language]);

            // if ($fetchLanguage = $selectReq->fetch()) {
            // insert into database
            try {
                $insertReq = $db->prepare("INSERT INTO language (language_name) VALUES (?)");
                $insertReq->execute([$language]);
            } catch (Exception $e) {
                // TODO: do something later maybe?
            }
            // TODO: SELECT the id of the language 
            $langSelect = $db->prepare("SELECT id FROM language WHERE language_name =?");
            $langSelect->execute([$language]);
            $langId = $langSelect->fetch()->id;

            // TODO: Using the id, insert an entry into the project_language_map table
            $req = $db->prepare("INSERT INTO project_language_map (project_id, language_id) VALUES (?, ?)");
            $req->execute([$project_id, $langId]);
        };
        $tagArray = explode(",", $tags);
        foreach ($tagArray as $tag) {

            try {
                $insertReq = $db->prepare("INSERT INTO tag (tag_name) VALUES (?)");
                $insertReq->execute([$tag]);
            } catch (Exception $e) {
                // TODO: do something later maybe?
                echo "error";
            }
            // TODO: SELECT the id of the tag 
            $tagSelect = $db->prepare("SELECT id FROM tag WHERE tag_name =?");
            $tagSelect->execute([$tag]);
            $tagId = $tagSelect->fetch()->id;
            // TODO: Using the id, insert an entry into the project_tag_map table
            $req = $db->prepare("INSERT INTO project_tag_map (project_id, tag_id) VALUES (?, ?)");
            $req->execute([$project_id, $tagId]);
        };
    }

    public function getCarousels()
    {
        $db = $this->dbConnect();

        $projects = $db->query("
        SELECT p.id as project_id, p.title, p.video_src, p.description, u.profile_img, u.username 
        FROM project p
        INNER JOIN user u
        ON p.user_id = u.id
        WHERE p.is_active = 1
        ORDER BY p.id ASC
        LIMIT 5        
        ");

        $arr = [];

        while ($project = $projects->fetch()) {
            array_push($arr, $project);
        }

        return $arr;
    }

    public function getMostRecentProjects($limit)
    {
        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id
            WHERE p.is_active = 1
            ORDER BY id DESC
            ";

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
        return array_slice($projects, 0, $limit);
    }

    public function getMostLikedProjects($limit)
    {
        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id
            WHERE p.is_active = 1
            ";

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
                $sum = $sumsQuery->fetch()->sum_stat ?? "0";
                $projects[$project_id]->sum = $sum;

                unset($projects[$project_id]->language_name);
            }
        }

        usort($projects, function ($first, $second) {
            return strcmp($second->sum, $first->sum);
        });

        return array_slice($projects, 0, $limit);
    }


    public function getProjectSearch($query)
    {

        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name, t.tag_name
            FROM user u
            INNER JOIN project p
            ON u.id = p.user_id
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON plm.language_id = l.id 
            INNER JOIN project_tag_map ptm
            ON ptm.project_id = p.id
            INNER JOIN tag t
            ON ptm.tag_id = t.id
            WHERE p.title LIKE :title OR 
                  l.language_name LIKE :language_name OR 
                  t.tag_name LIKE :tag_name";

        $res = $db->prepare($sql);

        $res->execute([
            'title' => "%" . $query . "%",
            'language_name' => "%" . $query . "%",
            'tag_name' => "%" . $query . "%"
        ]);

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
        return $projects;
    }

    public function getCount()
    {
        $db = $this->dbConnect();


        $arr = [];


        $project_count = $db->query("SELECT COUNT(*) AS count FROM project WHERE project.is_active = 1");


        $user_count = $db->query("SELECT COUNT(*) AS count FROM user");


        $lang_count = $db->query("SELECT COUNT(*) as count FROM language");


        array_push($arr, $project_count->fetch()->count);
        array_push($arr, $user_count->fetch()->count);
        array_push($arr, $lang_count->fetch()->count);




        return $arr;
    }

}
