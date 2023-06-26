<?php

require_once "Manager.php";
// TODO: Rename this and rename the file
class UserManager extends Manager
{
    // CREATING A NEW USER
    public function addUser($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $image_num = rand(0, 3);
        $images = ['./public/s_green.png', './public/s_yellow.png', './public/s_orange.png', './public/s_red.png'];
        $image_url = $images[$image_num];
        // add a default image
        $db = $this->dbConnect();
        $req = $db->prepare("INSERT INTO user (username, email, password, profile_img) VALUES (:username, :email, :password, :image_url)");
        $req->bindParam("username", $username, PDO::PARAM_STR);
        $req->bindParam("email", $email, PDO::PARAM_STR);
        $req->bindParam("password", $hashed_password, PDO::PARAM_STR);
        $req->bindParam("image_url", $image_url, PDO::PARAM_STR);
        $req->execute();
    }
    //HANDLING USER ERROR: MULTIPLE USERNAMES AND EMAILS
    public function userExists($username)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("SELECT COUNT(username) as count FROM user WHERE username = ?");
        $req->execute([$username]);


        return $req->fetch();
    }

    public function emailExists($email)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("SELECT COUNT(email) as count FROM user WHERE email = ?");
        $req->execute([$email]);


        return $req->fetch();
    }

    // INSERT NEW PROJECT
    public function insertNewProject($user_id, $gif, $title, $description, $tags, $languages)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("INSERT INTO project (user_id, gif, title, description)  VALUES (:user_id, :gif, :title, :description)"); //TODO:insert into tag table and language table)
        $req->bindParam("gif", $gif, PDO::PARAM_STR);
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

    public function logIn($username)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT profile_img, id, username, password, email FROM user WHERE username = :username");
        $req->execute([
            "username" => $username
        ]);

        $result = $req->fetch();
        // $array = [$result->id, $result->username, $result->email, $result->password, $result->profile_img];
        return $result;
    }

    // EDITING A USER
    public function submitEditedProfile(
        $id,
        $profile_image,
        $bio,
        $linked_in,
        $git_hub
    ) {
        $db = $this->dbConnect();
        $req = $db->prepare("UPDATE user 
                             SET 
                                 profile_img = :profile_img,
                                 bio = :bio,
                                 linkedIn = :linkedIn,
                                 gitHub = :gitHub   
                             WHERE id = :id");
        $req->bindParam("id", $id, PDO::PARAM_INT);
        $req->bindParam("profile_img", $profile_image, PDO::PARAM_STR);
        $req->bindParam("bio", $bio, PDO::PARAM_STR);
        $req->bindParam("linkedIn", $linked_in, PDO::PARAM_STR);
        $req->bindParam("gitHub", $git_hub, PDO::PARAM_STR);
        $req->execute();
        $_SESSION['profile_img'] = $profile_image;
    }

    public function submitPersonalInfo(
        $id,
        $first_name,
        $last_name,
        $username,
        $email
    ) {
        $db = $this->dbConnect();
        $req = $db->prepare("UPDATE user 
                             SET first_name = :first_name,
                                 last_name = :last_name,
                                 username = :username,
                                 email = :email
                             WHERE id = :id");
        $req->bindParam("id", $id, PDO::PARAM_INT);
        $req->bindParam("first_name", $first_name, PDO::PARAM_STR);
        $req->bindParam("last_name", $last_name, PDO::PARAM_STR);
        $req->bindParam("username", $username, PDO::PARAM_STR);
        $req->bindParam("email", $email, PDO::PARAM_STR);
        $req->execute();
    }

    public function submitChangePassword($id, $password)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("UPDATE user 
                             SET password = :password
                             WHERE id = :id");
        $req->bindParam("id", $id, PDO::PARAM_INT);
        $req->bindParam("password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
        $req->execute();
    }

    // 'deactivate' project with 'project_id' (set is_active = false)
    public function deleteProject($project_id)
    {
        $db = $this->dbConnect();

        $delete_req = $db->prepare("UPDATE project SET is_active = 0 WHERE id = ?");
        $delete_req->execute([$project_id]);
    }

    // get description, gif, title, id of project with project_id
    public function getProject($project_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare(
            "SELECT p.gif, p.description, p.title, p.id
            FROM project p
            WHERE id = ?"

        );

        $req->execute([$project_id]);

        return $req->fetch();
    }

    // get all languages used by project with 'project_id'
    public function getProjectLanguages($project_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare(
            "SELECT l.language_name as language_name
            FROM project p
            INNER JOIN project_language_map plm
            ON p.id = plm.project_id
            INNER JOIN language l
            ON l.id = language_id
            WHERE p.id = ?"
        );


        $req->execute([$project_id]);
        $languagesArr = [];

        while ($language = $req->fetch()) {
            array_push($languagesArr, $language->language_name);
        }

        return $languagesArr;
    }

    // get all tags of project with 'project_id'
    public function getProjectTags($project_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare(
            "SELECT t.tag_name
            FROM project p
            INNER JOIN project_tag_map ptm
            ON p.id = ptm.project_id
            INNER JOIN tag t
            ON ptm.tag_id = t.id
            WHERE p.id = ?"
        );

        $req->execute([$project_id]);
        $tagsArr = [];

        while ($language = $req->fetch()) {
            array_push($tagsArr, $language->tag_name);
        }

        return $tagsArr;
    }

    // update gif, desciption, title of project with 'project_id'
    public function updateProjectMain($gif, $description, $title, $project_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("UPDATE project SET gif = :gif, description = :description, title = :title WHERE id = :project_id");
        $req->execute(array(
            "gif" => $gif,
            "description" => $description,
            "title" => $title,
            "project_id" => $project_id
        ));
    }

    // update tags of project with 'project_id'
    public function updateProjectTags($tags, $project_id)
    {
        $db = $this->dbConnect();

        // clear project tag map table
        $deleteReq = $db->prepare("DELETE FROM project_tag_map WHERE project_id = ?");
        $deleteReq->execute([$project_id]);

        // insert into tag names into database 'tag'
        $tagsArr = explode(",", $tags);
        foreach ($tagsArr as $tag) {
            try {
                $req = $db->prepare("INSERT INTO tag (tag_name) VALUES (?)");
                $req->execute([$tag]);
            } catch (Exception $e) {
                continue;
            }
        }

        // insert new tag names into database 'project_tag_map'
        foreach ($tagsArr as $tag) {
            $tag_id_req = $db->prepare("SELECT id FROM tag WHERE tag_name = ?");
            $tag_id_req->execute([$tag]);
            $tag_id = $tag_id_req->fetch();

            $insertReq = $db->prepare("INSERT INTO project_tag_map (project_id, tag_id) VALUES(:project_id, :tag_id)");
            $insertReq->execute(array(
                "project_id" => $project_id,
                "tag_id" => $tag_id->id
            ));
        }
    }

    // update tags of project with 'project_id'
    public function updateProjectLanguages($languages, $project_id)
    {
        $db = $this->dbConnect();

        // clear project tag map table
        $deleteReq = $db->prepare("DELETE FROM project_language_map WHERE project_id = ?");
        $deleteReq->execute([$project_id]);

        // insert into tag names into database 'tag'
        $languagesArr = explode(
            ",",
            $languages
        );
        foreach ($languagesArr as $language) {
            try {
                $req = $db->prepare("INSERT INTO language (language_name) VALUES (?)");
                $req->execute([$language]);
            } catch (Exception $e) {
                continue;
            }
        }

        // insert new tag names into database 'project_tag_map'
        foreach ($languagesArr as $language) {
            $language_id_req = $db->prepare("SELECT id FROM language WHERE language_name = ?");
            $language_id_req->execute([$language]);
            $language_id = $language_id_req->fetch();

            $insertReq = $db->prepare("INSERT INTO project_language_map (project_id, language_id) VALUES(:project_id, :language_id)");
            $insertReq->execute(array(
                "project_id" => $project_id,
                "language_id" => $language_id->id
            ));
        }
    }

    // TODO: check how to use $_POST['id'] so that it will grab the information for the user
    public function getUserInfoProjects($user_id)
    {
        //i need to fetch all of the projects, languages, the profile pic, adctive status
        //where it all matches on the _id_id
        $db = $this->dbConnect();
        $sql = "SELECT u.id as user_id, u.profile_img, u.username, u.is_active, u.bio, u.gitHub, u.linkedIn, u.first_name, u.last_name, p.id as id, u.is_active, p.title, p.gif, p.description, l.language_name
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

        $profiles = [];
        while ($data = $res->fetch()) {
            $profile_id = $data->id;
            if (isset($profiles[$profile_id]) && $profiles[$profile_id] == $user_id) {
                $profiles[$profile_id]->languages[] = $data->language_name;
            } else {
                $profiles[$profile_id] = $data;
                $profiles[$profile_id]->languages = [];
                $profiles[$profile_id]->languages[] = $data->language_name;

                unset($profiles[$profile_id]->language_name);
            }
        }
        return $profiles;
    }

    // get user info if they have no projects
    public function getUserInfo($user_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("SELECT * FROM user WHERE id = ? ");
        $req->execute([$user_id]);

        return $req->fetch();
    }


    public function getUserLanguages($user_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("SELECT DISTINCT(l.language_name)
        FROM user u
        INNER JOIN project p
        ON u.id = p.user_id
        INNER JOIN project_language_map plm
        ON p.id = plm.project_id
        INNER JOIN language l
        ON plm.language_id = l.id
        WHERE u.id = ?
        ");

        $req->execute([$user_id]);

        $userLanguages = [];
        while ($language = $req->fetch()) {
            array_push($userLanguages, $language->language_name);
        }

        return $userLanguages;
    }
}
