<?php

namespace app\controllers;

class OpinionController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addNewOpinionEvent($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al añadir tu comentario";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO opinion(user, event, score, comment, date_opinion)"
                    . " VALUES"
                    . " (:user, :event, :score, :comment, :date_opinion)";
            $dataQuery = array(
                "user" => $data["user"],
                "event" => $data["event"],
                "score" => $data["score"],
                "comment" => $data["comment"],
                "date_opinion" => $data["dateOpinion"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Gracias por comentar";
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    public function getEventReviews($data) {
        $response = new \app\entities\Response();
        $messageResponse = "Problemas para obtener los comentarios";
        $isOk = FALSE;
        try {
            $query = "SELECT u.username,u.photo_url,ev.score,ev.comment,ev.date_opinion FROM opinion as ev inner join user as u on ev.user=u.email WHERE ev.event = :id";
            $dataQuery = array("id" => (int) $data["id"]);

            $eventVotes = $this->connectionDb->executeQueryWithDataFetchAll($query, $dataQuery);

            if ($eventVotes) {
                $arrayEventVotesFinal = array();
                for ($i = 0; $i < count($eventVotes); $i++) {
                    $eventVote = new \app\entities\Opinion();
                    $eventVote->setUsername($eventVotes[$i]["username"]);
                    $eventVote->setPhoto_name($eventVotes[$i]["photo_url"]);
                    $eventVote->setScore($eventVotes[$i]["score"]);
                    $eventVote->setComment($eventVotes[$i]["comment"]);
                    $eventVote->setDateOpinion($eventVotes[$i]["date_opinion"]);
                    array_push($arrayEventVotesFinal, $eventVote->getArray());
                }
                $isOk = TRUE;
                $messageResponse = "";
                $response->setContent(json_encode($arrayEventVotesFinal));
            } else if (count($eventVotes) == 0) {
                $messageResponse = "No hay opiniones registradas para esta carrera";
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }

        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }
    
    //Comprobar el trigger para que modifique solo el score y el rating de la tabla event en caso de editar la opinion
    public function updateOpinion($data) {
        $response = new \app\entities\Response();
        $messageResponse = "No se ha podido editar tu comentario";
        $isOk = FALSE;
        try {
            $query = "UPDATE opinion SET score = :score, comment = :comment, date_opinion = :date_opinion"
                    . " WHERE"
                    . " user = :user AND event = :event";
            $dataQuery = array(
                "user" => $data["user"],
                "event" => $data["event"],
                "score" => $data["score"],
                "comment" => $data["comment"],
                "date_opinion" => $data["dateOpinion"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse="";
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }
    
    public function deleteOpinion($args) {
        $response = new \app\entities\Response();
        $messageResponse = "No se ha podido eliminar tu comentario";
        $isOk = FALSE;
        try {
            $query = "DELETE FROM opinion"
                    . " WHERE"
                    . " user = :user AND event = :event";
            $dataQuery = array(
                "user" => $args["user"],
                "event" => $args["event"]);
            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse="";
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

}
