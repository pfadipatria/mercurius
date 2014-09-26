<?php

namespace SKeyManager\Entity;

class Lock extends AbstractEntity {

    protected $locationPattern = '/lock/%s';

    function __construct($id = null) {
        $this->select = '
            SELECT
               lock.id AS id,
               number,
               lock.name AS name,
               sc AS code,
               comment,
               lastupdate,
               type,
               position,
               hasbatteries,
               place.name AS venuename,
               place.id AS venueid,
               lock.status AS statusid,
               lockstatus.name AS statusname
        ';

        $this->from = '
               FROM `lock`
               LEFT JOIN `place` ON (lock.place = place.id)
               LEFT JOIN `lockstatus` ON (lock.status = lockstatus.id)
        ';
        $this->where = '
               WHERE lock.id = '.$id.'
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
   function __get($arg) {
      return null;
   }

   function getId(){
      return $this->id;
   }

   function getCode(){
      return $this->code;
   }

   function setCode($code){
      $this->code = $code;
      return $this;
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

   function setName($name){
      $this->name = $name;
      return $this;
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

   function getVenueId(){
      return $this->venueid;
   }

   function setVenueId($venueid){
      $this->venueid = $venueid;
      return $this;
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

   function getHasBatteries(){
      return $this->hasbatteries;
   }

   function setHasBatteries($hasbatteries){
      $this->hasbatteries = $hasbatteries;
      return $this;
   }

   function getType(){
      return $this->type;
   }

   function setType($type){
      $this->type = $type;
      return $this;
   }

   function getPosition(){
      return $this->position;
   }

   function setPosition($position){
      $this->position = $position;
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
      $dbTable = 'lock';
      $con = openDb();

      $data = array();
      $data['number'] = $this->getNumber() ? '"'.mysqli_real_escape_string($con, $this->getNumber()).'"' : 'NULL';
      $data['status'] = $this->getStatusId() ? '"'.mysqli_real_escape_string($con, $this->getStatusId()).'"' : 'NULL';
      $data['sc'] = $this->getCode() ? '"'.mysqli_real_escape_string($con, $this->getCode()).'"' : 'NULL';
      $data['place'] = $this->getVenueId() ? '"'.mysqli_real_escape_string($con, $this->getVenueId()).'"' : 'NULL';
      $data['hasbatteries'] = $this->getHasBatteries() ? '"'.mysqli_real_escape_string($con, $this->getHasBatteries()).'"' : 'NULL';
      $data['name'] = $this->getName() ? '"'.mysqli_real_escape_string($con, $this->getName()).'"' : 'NULL';
      $data['type'] = $this->getType() ? '"'.mysqli_real_escape_string($con, $this->getType()).'"' : 'NULL';
      $data['position'] = $this->getPosition() ? '"'.mysqli_real_escape_string($con, $this->getPosition()).'"' : 'NULL';
      $data['comment'] = $this->getComment() ? '"'.mysqli_real_escape_string($con, $this->getComment()).'"' : 'NULL';

      if($this->getId()) {
         $idString = ' id = '.mysqli_real_escape_string($con, $this->getId());
         return $this->updateDb($con, $dbTable, $data, $idString);
      } else {
         return $this->insertDb($con, $dbTable, $data);
      }

   }
}
