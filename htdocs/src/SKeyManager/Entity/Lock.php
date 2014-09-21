<?php

namespace SKeyManager\Entity;

class Lock extends AbstractEntity {

    protected $locationPattern = '/lock/%s';

    function __construct($lockId = null) {
        $this->select = '
            SELECT
               doorlock.id AS id,
               number,
               doorlock.name AS lockname,
               sc AS code,
               comment,
               lastupdate,
               type,
               position,
               hasbatteries,
               doorplace.name AS venuename,
               doorplace.id AS venueid,
               doorlockstatus.id AS statusid,
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

   function getStatus() {
      return $this->getStatusName();
   }

   function getStatusName(){
      return $this->statusname;
   }

   function getComment(){
      return $this->comment;
   }

   function getLastUpdate(){
      return $this->lastupdate;
   }

   function getName() {
      $name = 'MC '.$this->getCode();
      $name .= $this->getOwnerName() ? ' - '.$this->getOwnerName() : '';
      return $name;
   }
}
