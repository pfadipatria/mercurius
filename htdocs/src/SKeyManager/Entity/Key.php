<?php

namespace SKeyManager\Entity;

class Key extends AbstractEntity {

    protected $locationPattern = '/key/%s';
   var $owner;
   var $allowedLocks;
   var $denyingLocks;

    function __construct($id = null) {
        $this->select = '
            SELECT
               doorkey.id,
               elnumber,
               code,
               type,
               doorkeycolor.name AS colorname,
               doorkeycolor.id AS colorid,
               doorkeystatus.name AS statusname,
               doorkeystatus.id AS statusid,
               doorkeymech.bezeichung AS description,
               owner AS ownerid,
               doorkey.comment AS comment,
               communication,
               doorkey.lastupdate AS lastupdate
        ';

        $this->from = '
            FROM doorkey
            LEFT JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id )
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
        ';

      $this->where = '
         WHERE doorkey.id = '.$id.'
      ';

        $this->order = '
            ORDER BY doorkey.code
        ';
    }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }

      if(!empty($this->ownerid)) $this->owner = new \SKeyManager\Entity\Person($this->ownerid);
   }

   function getId(){
      return $this->id;
   }

   function getCode(){
      return $this->code;
   }

   function getStatusName(){
      return $this->statusname;
   }

   function getOwnerName(){
      return $this->owner->getName();
   }

   function getComment(){
      return $this->comment;
   }

   function getName() {
      $name = 'MC '.$this->getCode;
      $this->getOwnerName ? $name .= ' - '.$this->getOwnerName();
      return $name;
   }

   function getAllowedLocks() {
      return $this->allowedLocks;
   }


///////////////////////////////////////////////////////
   function getPermissions() {
      $return = '';
      $this->select = 'SELECT
         doorkey_opens_lock.lock AS lockid,
         doorlock.sc AS locksc,
         doorplace.name AS heim,
         doorlock.name AS lockname
         ';
      $this->from = '
         FROM doorkey
         LEFT JOIN doorkey_opens_lock ON (doorkey.id = doorkey_opens_lock.key )
         LEFT JOIN doorlock ON (doorkey_opens_lock.lock = doorlock.id )
         LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
         ';
      $rows = parent::getAll();

      return $return;
   }

/*
   function getName() {
      $return = '';
      $this->select = 'SELECT code, doorperson.name AS owner';
      $row = parent::getAll();
      $return .= 'MC '.$row['code'];
      if(!empty($row['owner'])) $return .= ' - '.$row['owner'];

      return $return;
   }


    protected function query($where = ' WHERE true ') {
      $where .= ' AND doorkey.id = '.$this->id;
      error_log($this->select.$this->from.$where.$this->order);
      $con = openDb();
      $dbresult = queryDb($con, $this->select.$this->from.$where.$this->order);
      $row = array();
      $locations = array();
      $row = mysqli_fetch_assoc($dbresult);
      return $row;

    }
*/
}
