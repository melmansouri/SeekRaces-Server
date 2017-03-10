<?php

namespace app\controllers;

class FavoriteController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addEventToFavorite($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al añadir la carrera como favorita";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO favorite(user, event)"
                    . " VALUES"
                    . " (:user, :event)";
            $dataQuery = array("user" => $data["user"],
                "event" => $data["event"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "La carrera ha sido añadida como favorita";
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
    
    public function getEventsFavorites($data){
        $response = new \app\entities\Response();
        $messageResponse = "error al obtener las carreras favoritas";
        $isOk = FALSE;
        try {
            $columnsResultQuery = "e.id,e.user,u.username,u.photo_url,u.place, "
                    . "(SELECT if(fo.follower_user is null,false,true) from follow fo where fo.follower_user = :user and fo.followed_user = e.user) as isFollowed "
                    . ",e.name,e.description,e.image,e.distance,e.place,e.date_time_init,e.web, "
                    . "(e.date_time_init < NOW()) as finished";
            //$columnsResultQuery="e.id,e.user,u.username,e.name,e.description,e.image,e.distance,e.place,e.date_time_init,e.web,(e.date_time_init < NOW()) as finished";
            $query = "SELECT ".$columnsResultQuery." FROM favorite as f inner join event as e inner join user as u on f.event = e.id and e.user = u.email WHERE f.user= :user order by e.date_time_init asc";
            $dataQuery = array("user" => $data["email"]);
            
            $eventos=$this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);
            
            if ($eventos) {
                $arrayEventosFinal= array();
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
                    $event->setIsFavorite(1);
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
                $messageResponse="";
                $response->setContent(json_encode($arrayEventosFinal));
            }else{
                $isOk = TRUE;
                $messageResponse="No tienes carreras favoritas.";
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
    
    public function deleteEventFromFavorites($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al intentar borrar la carrera de favoritos";
        $isOk = FALSE;
        try {
            $query = "DELETE FROM favorite WHERE event = :event and user = :user";
            $dataQuery = array("event" => $data["id"],
                "user" => $data["email"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Se ha eliminado con éxito de favoritos";
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
