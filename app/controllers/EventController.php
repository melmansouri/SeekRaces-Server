<?php

namespace app\controllers;

class EventController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addNewEvent($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al añadir la carrera. Intentalo más tarde";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO event(user, name, description, image,distance,place,date_time_init,web)"
                    . " VALUES"
                    . " (:user, :name, :description, :image,:distance,:place,:date_time_init,:web)";
            $imageName = "";
            if (isset($data["imageBase64"]) && !empty($data["imageBase64"])) {
                $file_path_photo = \app\common\Utils::base64ToFile($data["imageBase64"], \app\common\Utils::getCurrentMilliseconds());
                $imageName = $file_path_photo;
            }
            
            $race= new \app\entities\Race();
            $user=new \app\entities\User();
            $user->setEmail($data["userEmail"]);
            $race->setName($data["name"]);
            $race->setImageName($imageName);
            $race->setDescription($data["description"]);
            $race->setDistance($data["distance"]);
            $race->setPlace($data["place"]);
            $race->setDate_time_init($data["date_time_init"]);
            $race->setWeb($data["web"]);
            $userController=new \app\controllers\UserController($this->connectionDb);
            
            $userFromDb=$userController->checkExistUser($data["userEmail"]);
            
            if ($userFromDb) {
                $user->setUsername($userFromDb->username);
            }
            
            $race->setUser(json_encode($user->getArray()));

            $dataQuery = array("user" => $data["userEmail"],
                "name" => $data["name"],
                "description" => $data["description"],
                "image" => $imageName,
                "distance" => $data["distance"],
                "place" => $data["place"],
                "date_time_init" => $data["date_time_init"],
                "web" => $data["web"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $this->sendNotificationToFollowers($data["userEmail"],$race->getArray());
                $messageResponse = "Carrera añadida";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function sendNotificationToFollowers($userFollowed, $data) {
        try {
            $tokens = $this->getTokenPushUsersFollowers($userFollowed);
            if ($tokens) {
                $gcmRegIds = array();
                print_r($tokens);
                for ($i = 0; $i < count($tokens); $i++) {
                    $token = $tokens[$i]["token_push"];
                    $setNotificacion = $tokens[$i]["sentNotificacion"];
                    if (isset($token) && !empty($token)) {
                        if (isset($setNotificacion) && !empty($setNotificacion)) {
                            if ($setNotificacion==1) {
                                array_push($gcmRegIds, $token);
                            }
                        }
                    }
                }
                print_r($gcmRegIds);
                if (count($gcmRegIds) > 0) {
                    $result = \app\common\Utils::send_notification($gcmRegIds, $data);
                    print_r($result);
                }
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        }
    }

    private function getTokenPushUsersFollowers($email) {
        $result = FALSE;
        try {
            $query = 'SELECT u.token_push,f.sentNotificacion FROM follow f inner join user u on f.follower_user=u.email WHERE f.followed_user=:followed';
            $dataQuery = array('followed' => $email);
            $result = $this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);
        } catch (Exception $ex) {
            print $pex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }
        return $result;
    }

    public function getEvents($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Problemas para obtener las carreras. Intentalo más tarde";
        $isOk = FALSE;
        try {
            $columnsResultQuery = "e.id,e.user,u.username,u.photo_url,u.place, "
                    . "(SELECT if(f.follower_user is null,false,true) from follow f where f.follower_user = :user and f.followed_user = e.user) as isFollowed "
                    . ",e.name,e.description,e.image,e.distance,e.place,e.date_time_init,e.web, "
                    . "(select if(f.event is null,false,true) from favorite f where f.event=e.id and f.user = :user) as favorite, (e.date_time_init < NOW()) as finished";
            $query = "SELECT " . $columnsResultQuery . " FROM event e inner join user u ON e.user=u.email WHERE e.place like :place and e.name like :name ";
            $dataQuery = array("user" => $data["user"],
                "place" => "%%",
                "name" => "%%");


            if (array_key_exists("place", $data)) {
                $dataQuery["place"] = "%" . $data["place"] . "%";
            }

            if (array_key_exists("name", $data)) {
                $dataQuery["name"] = "%" . $data["name"] . "%";
            }

            //if (array_key_exists("distance", $data)) {
            if ($data["distanceMin"] != 0 && $data["distanceMax"] != 0) {
                $query .= " AND e.distance BETWEEN :distanceMin and :distanceMax";
                $dataQuery["distanceMin"] = $data["distanceMin"];
                $dataQuery["distanceMax"] = $data["distanceMax"];
            }

            if (array_key_exists("date_interval_init", $data) && !array_key_exists("date_interval_end", $data)) {
                $query .= " AND e.date_time_init > :date_interval_init order by e.date_time_init asc";
                $dataQuery["date_interval_init"] = $data["date_interval_init"];
            } else if (array_key_exists("date_interval_init", $data) && array_key_exists("date_interval_end", $data)) {
                $query .= " AND e.date_time_init between :date_interval_init AND :date_interval_end order by e.date_time_init asc";
                $dataQuery["date_interval_init"] = $data["date_interval_init"];
                $dataQuery["date_interval_end"] = $data["date_interval_end"];
            } else if (!array_key_exists("date_interval_init", $data) && !array_key_exists("date_interval_end", $data)) {
                $query .= " AND e.date_time_init > NOW() order by e.date_time_init asc";
            }
            echo $query;
            $eventos = $this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);

            if ($eventos) {
                $arrayEventosFinal = array();
                for ($i = 0; $i < count($eventos); $i++) {
                    $event = new \app\entities\Race();
                    $event->setId($eventos[$i]["id"]);
                    //$event->setUser($eventos[$i]["user"]);
                    $event->setUserName($eventos[$i]["username"]);
                    $event->setName($eventos[$i]["name"]);
                    $event->setDescription($eventos[$i]["description"]);
                    $imageName = $eventos[$i]["image"];
                    //$base64 = \app\common\Utils::fileToBase64($imageName);
                    $event->setImageName($imageName);
                    //$event->setImageBase64($base64);
                    $event->setDistance($eventos[$i]["distance"]);
                    $event->setPlace($eventos[$i]["place"]);
                    $event->setDate_time_init($eventos[$i]["date_time_init"]);
                    $event->setWeb($eventos[$i]["web"]);
                    $event->setIsFavorite($eventos[$i]["favorite"]);
                    $event->setIsFinished($eventos[$i]["finished"]);
                    $user = new \app\entities\User();
                    $user->setEmail($eventos[$i]["user"]);
                    $user->setPhoto_url($eventos[$i]["photo_url"]);
                    $user->setPlace($eventos[$i]["place"]);
                    $user->setUsername($eventos[$i]["username"]);
                    $user->setIsFollowed($eventos[$i]["isFollowed"]);

                    if ($user->getIsFollowed() !== null && $user->getIsFollowed() === 1) {
                        $follow = $this->connectionDb->executeQueryWithoutDataFetchAll("select sentNotificacion from follow where follower_user like " . $data["user"] . " and followed_user like " . $user->getEmail());
                        if ($follow) {
                            $user->setIsSentNotificacion($follow);
                        } else {
                            $user->setIsSentNotificacion(0);
                        }
                    }


                    $event->setUser(json_encode($user->getArray()));
                    array_push($arrayEventosFinal, $event->getArray());
                }
                $isOk = TRUE;
                $messageResponse = "";
                $response->setContent(json_encode($arrayEventosFinal));
            } else {
                $isOk = TRUE;
                $messageResponse = "No hay carreras para este filtro. Intenta cambiar el fitro de búsqueda o sé el primero en publicar";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function getFinishedEvents() {
        $response = new \app\entities\Response();
        $messageResponse = "Problemas para obtener las carreras finalizadas. Intentalo más tarde";
        $isOk = FALSE;
        try {
            $columnsResultQuery = "e.id,e.user,u.username,e.name,e.description,e.image,e.distance,e.place,e.date_time_init,e.web";
            $query = "SELECT " . $columnsResultQuery . " FROM event e inner join user u on e.user=u.email WHERE e.date_time_init < NOW() order by e.date_time_init desc";
            //$dataQuery = array("user" => $data["email"]);
            $eventos = $this->connectionDb->executeQueryWithoutDataFetchAll($query);

            if ($eventos) {
                $arrayEventosFinal = array();
                for ($i = 0; $i < count($eventos); $i++) {
                    $event = new \app\entities\Race();
                    $event->setId($eventos[$i]["id"]);
                    $event->setUser($eventos[$i]["user"]);
                    $event->setUserName($eventos[$i]["username"]);
                    $event->setName($eventos[$i]["name"]);
                    $event->setDescription($eventos[$i]["description"]);
                    $imageName = $eventos[$i]["image"];
                    $base64 = \app\common\Utils::fileToBase64($imageName);
                    $event->setImageBase64($base64);
                    //$event->setImageName($imageName);
                    $event->setDistance($eventos[$i]["distance"]);
                    $event->setPlace($eventos[$i]["place"]);
                    $event->setDate_time_init($eventos[$i]["date_time_init"]);
                    $event->setWeb($eventos[$i]["web"]);
                    /* $event->setNum_reviews($eventos[$i]["num_reviews"]);
                      $event->setTotal_scores($eventos[$i]["total_scores"]);
                      $event->setRating($eventos[$i]["rating"]); */
                    array_push($arrayEventosFinal, $event->getArray());
                }
                $isOk = TRUE;
                $messageResponse = "";
                $response->setContent(json_encode($arrayEventosFinal));
            } else {
                $isOk = TRUE;
                $messageResponse = "No tienes carreras publicadas.";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }
        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function getOwnEvents($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Problemas para obtener tus carreras. Intentalo más tarde";
        $isOk = FALSE;
        try {
            $columnsResultQuery = "e.id,e.user,u.username,u.photo_url,u.place, "
                    . "e.name,e.description,e.image,e.distance,e.place,e.date_time_init,e.web, "
                    . "(e.date_time_init < NOW()) as finished";
            //$columnsResultQuery = "e.id,e.user,e.name,e.description,e.image,e.distance,e.place,e.date_time_init,e.web,(e.date_time_init < NOW()) as finished";
            $query = "SELECT " . $columnsResultQuery . " FROM event e inner join user u on e.user=u.email WHERE e.user = :user order by e.date_time_init asc";
            $dataQuery = array("user" => $data["email"]);
            $eventos = $this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);

            if ($eventos) {
                $arrayEventosFinal = array();
                for ($i = 0; $i < count($eventos); $i++) {
                    $event = new \app\entities\Race();
                    $event->setId($eventos[$i]["id"]);
                    //$event->setUser($eventos[$i]["user"]);
                    $event->setUserName("Mi");
                    $event->setName($eventos[$i]["name"]);
                    $event->setDescription($eventos[$i]["description"]);
                    $imageName = $eventos[$i]["image"];
                    //$base64 = \app\common\Utils::fileToBase64($imageName);
                    $event->setImageName($imageName);
                    //$event->setImageBase64($base64);
                    $event->setDistance($eventos[$i]["distance"]);
                    $event->setPlace($eventos[$i]["place"]);
                    $event->setDate_time_init($eventos[$i]["date_time_init"]);
                    $event->setWeb($eventos[$i]["web"]);
                    $event->setIsFinished($eventos[$i]["finished"]);
                    $user = new \app\entities\User();
                    $user->setEmail($eventos[$i]["user"]);
                    $user->setPhoto_url($eventos[$i]["photo_url"]);
                    $user->setPlace($eventos[$i]["place"]);
                    $user->setUsername($eventos[$i]["username"]);
                    $event->setUser(json_encode($user->getArray()));
                    array_push($arrayEventosFinal, $event->getArray());
                }
                $isOk = TRUE;
                $messageResponse = "";
                $response->setContent(json_encode($arrayEventosFinal));
            } else {
                $isOk = TRUE;
                $messageResponse = "No tienes carreras publicadas.";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }
        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function deleteEvent($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al intentar borrar la carrera";
        $isOk = FALSE;
        try {
            $query = "DELETE FROM event WHERE user = :user and id = :id";
            $dataQuery = array("id" => $data["id"],
                "user" => $data["email"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Se ha eliminado con éxito";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function editEvent($data) {
        $response = new \app\entities\Response();
        $messageResponse = "No se ha podido editar la carrera";
        $isOk = FALSE;
        try {
            $query = "UPDATE event SET name = :name, description = :description, distance = :distance, place = :place, date_time_init = :date_time_init, web = :web";

            $whereQuery = " WHERE user = :user AND id = :id";

            $dataQuery = array(
                "user" => $data["user"],
                "id" => $data["id"],
                "name" => $data["name"],
                "description" => $data["description"],
                "distance" => $data["distance"],
                "place" => $data["place"],
                "date_time_init" => $data["date_time_init"],
                "web" => $data["web"]);

            $imageName = "";
            if (isset($data["imageBase64"]) && !empty($data["imageBase64"])) {
                $file_path_photo = \app\common\Utils::base64ToFile($data["imageBase64"], \app\common\Utils::getCurrentMilliseconds());
                $imageName = $file_path_photo;
                $query .= ", image = :image";
                $dataQuery["image"] = $imageName;
            }

            $query .= $whereQuery;

            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Éxito al editar la carrera";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

}
