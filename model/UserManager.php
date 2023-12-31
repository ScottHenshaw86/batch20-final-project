<?php

require_once "Manager.php";
// TODO: Rename this and rename the file
class UserManager extends Manager
{
    // CREATING A NEW USER
    public function addUser($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $image_num = rand(0, 7);
        $images = ['./public/pfp_default/pfp1.jpg', './public/pfp_default/pfp2.jpg', './public/pfp_default/pfp3.jpg', './public/pfp_default/pfp4.jpg', './public/pfp_default/pfp5.jpg', './public/pfp_default/pfp6.jpg', './public/pfp_default/pfp7.jpg', './public/pfp_default/pfp8.jpg'];
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

    // INSERTING THE DATA INTO THE DATABASE WHEN SIGNED IN WITH GOOGLE 
    public function addUserGoogle($username, $given_name, $family_name, $email, $picture)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("INSERT INTO user (username, email, profile_img, first_name, last_name) VALUES (:username, :email, :picture, :first_name, :last_name)");
        $req->bindParam("username", $username, PDO::PARAM_STR);
        $req->bindParam("first_name", $given_name, PDO::PARAM_STR);
        $req->bindParam("last_name", $family_name, PDO::PARAM_STR);
        $req->bindParam("email", $email, PDO::PARAM_STR);
        $req->bindParam("picture", $picture, PDO::PARAM_STR);
        $req->execute();

        // FOR RETURNING THE 'ID' OF THE LAST INSERT
        return $db->lastInsertId();
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
        $bio,
        $linked_in,
        $git_hub
    ) {
        $db = $this->dbConnect();
        $req = $db->prepare("UPDATE user 
                             SET 
                                 bio = :bio,
                                 linkedIn = :linkedIn,
                                 gitHub = :gitHub   
                             WHERE id = :id");
        $req->bindParam("id", $id, PDO::PARAM_INT);
        $req->bindParam("bio", $bio, PDO::PARAM_STR);
        $req->bindParam("linkedIn", $linked_in, PDO::PARAM_STR);
        $req->bindParam("gitHub", $git_hub, PDO::PARAM_STR);
        $req->execute();
    }

    public function uploadProfilePicture($id, $target_file)
    {
        // $picture_path = "./public/profile_images/" . $profile_img['full_path'];
        // echo "hello! targetfile: " . $target_file;

        $db = $this->dbConnect();
        $req = $db->prepare("UPDATE user 
                             SET 
                                 profile_img = :profile_img 
                             WHERE id = :id");
        $req->bindParam("id", $id, PDO::PARAM_INT);
        $req->bindParam("profile_img", $target_file, PDO::PARAM_STR);
        $req->execute();
        $_SESSION['profile_img'] = $target_file;
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
            "SELECT p.video_src, p.description, p.title, p.id
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
    public function updateProjectMain($video_source, $description, $title, $project_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("UPDATE project SET video_src = :video_src, description = :description, title = :title WHERE id = :project_id");
        $req->execute(array(
            "video_src" => $video_source,
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
        $sql = "SELECT u.id as user_id, u.profile_img, u.username, u.is_active, u.bio, u.gitHub, u.linkedIn, u.first_name, u.last_name, p.id as id, u.is_active, p.title, p.video_src, p.description, l.language_name
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

    // FUNCTION FOR GOOGLE SIGN IN BASED ON USERNAME
    public function getUserInfoGoogle($username)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("SELECT * FROM user WHERE username = ? ");
        $req->execute([$username]);

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

    public function getUserLikes($user_id)
    {
        $db = $this->dbConnect();

        $req = $db->prepare("
        SELECT SUM(pv.stat) as likes
        FROM project p
        INNER JOIN project_votes pv
        ON p.id = pv.project_id
        WHERE p.user_id = ?");

        $req->execute([$user_id]);

        // $likes = $req->fetch();
        // if ($likes) {
        //     return $likes
        // }

        $likes = $req->fetch()->likes;

        return $likes ?? "0";
    }
}
