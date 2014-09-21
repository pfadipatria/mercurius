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

   function getNumber(){
      return $this->number;
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

   function getComment(){
      return $this->comment;
   }

   function getLastUpdate(){
      return $this->lastupdate;
   }

}
