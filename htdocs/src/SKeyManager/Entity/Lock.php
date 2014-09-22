<?php

namespace SKeyManager\Entity;

class Lock extends AbstractEntity {

    protected $locationPattern = '/lock/%s';

    function __construct($id = null) {
        $this->select = '
            SELECT
               doorlock.id AS id,
               number,
               doorlock.name AS name,
               sc AS code,
               comment,
               lastupdate,
               type,
               position,
               hasbatteries,
               doorplace.name AS venuename,
               doorplace.id AS venueid,
               doorlock.status AS statusid,
               doorlockstatus.name AS statusname
        ';

        $this->from = '
               FROM doorlock
               LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
               LEFT JOIN doorlockstatus ON (doorlock.status = doorlockstatus.id)
        ';
        $this->where = '
               WHERE doorlock.id = '.$id.'
        ';
        $this->order = '
               ORDER BY sc
        ';
    }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }
   }

   function getId(){
      return $this->id;
   }

   function getCode(){
      return $this->code;
   }

   function getNumber(){
      return $this->number;
   }

   function setNumber($number){
      $this->number = $number;
      return $this;
   }

   function getName(){
      return $this->name;
   }

   function getFullName(){
      $name = 'SC '.$this->getCode();
      $name .= $this->getVenue() ? ' - '.$this->getVenue() : '';
      $name .= $this->getName() ? ' '.$this->getName() : '';
      return $name;
   }

   function getVenue(){
      return $this->venuename;
   }

   function getStatus() {
      return $this->getStatusName();
   }

   function getStatusName(){
      return $this->statusname;
   }

   function getStatusId(){
      return $this->statusid;
   }

   function setStatusId($statusid){
      $this->statusid = $statusid;
      return $this;
   }

   function getComment(){
      return $this->comment;
   }

   function setComment($comment){
      $this->comment = $comment;
      return $this;
   }

   function getLastUpdate(){
      return $this->lastupdate;
   }

   function save() {
      $idString = '';
      $con = openDb();
      if($this->getId()) {
         $idString = ' id = '.mysqli_real_escape_string($con, $this->getId());
         return $this->updateDb($con, $idString);
      } else {
         return $this->insertDb($con);
      }

   }

   function updateDb($con, $idString) {
      $number = $this->getNumber() ? '"'.mysqli_real_escape_string($con, $this->getNumber()).'"' : 'NULL';
      $statusid = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $comment = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      $sql = '
         UPDATE doorlock
         SET
            number = '.$number.',
            status = '.$statusid.',
            comment = '.$comment.'
            WHERE '.$idString.'
         ';
      $dbresult = queryDb($con, $sql);
      return $dbresult;
   }

   function insertDb($con) {
      $number = $this->getNumber() ? '"'.mysqli_real_escape_string($con, $this->getNumber()).'"' : 'NULL';
      $statusid = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $comment = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      $sql = '
         INSERT INTO doorlock
            number,
            status,
            comment
         VALUES(
            '.$number.',
            '.$statusid.',
            '.$comment.'
            )
         ';
      $dbresult = queryDb($con, $sql);
      if ($dbresult) {
         $this->id = mysqli_insert_id($con);
      }
      return $dbresult;
   }


}
