<?php

namespace app\controllers;

class OpinionController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function addNewOpinionEvent($args, $data) {
        $response = new \app\entities\Response();
        $messageResponse = "Error al añadir tu comentario";
        $isOk = FALSE;
        try {
            $query = "INSERT INTO opinion(user, event, score, comment, dateTime_vote)"
                    . " VALUES"
                    . " (:user, :event, :score, :comment, :dateTime_vote)";
            $dataQuery = array(
                "user" => $data["user"],
                "event" => $args["id"],
                "score" => $data["score"],
                "comment" => $data["comment"],
                "dateTime_vote" => $data["dateTime_vote"]);
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
                    $eventVote->setPhoto_name($eventVotes[$i]["photo"]);
                    $eventVote->setScore($eventVotes[$i]["score"]);
                    $eventVote->setComment($eventVotes[$i]["comment"]);
                    $eventVote->setDateOpinion($eventVotes[$i]["dateTime_vote"]);
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
    public function updateOpinion($args, $data) {
        $response = new \app\entities\Response();
        $messageResponse = "No se ha podido editar tu comentario";
        $isOk = FALSE;
        try {
            $query = "UPDATE opinion SET score = :score, comment = :comment, dateTime_vote = :dateTime_vote"
                    . " WHERE"
                    . " user = :user AND event = :event";
            $dataQuery = array(
                "user" => $data["user"],
                "event" => $args["id"],
                "score" => $data["score"],
                "comment" => $data["comment"],
                "dateTime_vote" => $data["dateTime_vote"]);
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
