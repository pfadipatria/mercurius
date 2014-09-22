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

   // Needed to provide a Name of a 'empty' lock ?
   function __get($arg = null) {
      return null;
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

      $data = array();
      $data['number'] = $this->getNumber() ? '"'.mysqli_real_escape_string($con, $this->getNumber()).'"' : 'NULL';
      $data['status'] = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $data['comment'] = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      if($this->getId()) {
         $idString = ' id = '.mysqli_real_escape_string($con, $this->getId());
         return $this->updateDb($con, $data, $idString);
      } else {
         return $this->insertDb($con, $data);
      }

   }

   function updateDb($con, $data, $idString) {
      $sql = '
         UPDATE doorlock
         SET
            '.$idString.'
            ';
         foreach($data as $key => $value) {
            $sql .= '
               , '.$key.' = '.$value.'
               ';
            }
         $sql .= '
            WHERE
               '.$idString.'
            ';

      $dbresult = queryDb($con, $sql);
      return $dbresult;
   }

   function insertDb($con, $data) {
      $number = $this->getNumber() ? '"'.mysqli_real_escape_string($con, $this->getNumber()).'"' : 'NULL';
      $statusid = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $comment = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      $sql = '
         INSERT INTO doorlock
            ( lastupdate ';
      $keys = '';
      $values = '';
      var_dump($data);
      foreach($data as $key => $value){
         $keys .= ' , '.$key.;
         $values .= ' , '.$value;
      }
      $sql .= $keys.' ) VALUES( VALUES(CURRENT_TIMESTAMP() '.$values.' )';
      var_dump($sql);
      $dbresult = queryDb($con, $sql);
      if ($dbresult) {
         $this->id = mysqli_insert_id($con);
      }
      return $dbresult;
   }


}
