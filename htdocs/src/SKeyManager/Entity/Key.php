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
            LEFT JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id)
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
            LEFT JOIN doorkeymech ON (doorkey.mech = doorkeymech.id)
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

      $owner = null;
      if(!empty($this->ownerid)) {
         $owner = new \SKeyManager\Entity\Person($this->ownerid);
         $owner->load();
      } else {
         $owner = new \SKeyManager\Entity\Person();
      }
      $this->owner = $owner;
   }

   function getId(){
      return $this->id;
   }

   function getCode(){
      return $this->code;
   }

   function getElNumber(){
      return $this->elnumber;
   }

   function getStatus() {
      return $this->getStatusName();
   }

   function getStatusName(){
      return $this->statusname;
   }

   function getOwner() {
      return $this->owner;
   }

   function getOwnerName(){
      return $this->getOwner()->getName();
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
