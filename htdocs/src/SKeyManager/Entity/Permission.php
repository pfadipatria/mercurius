<?php

namespace SKeyManager\Entity;

class Permission extends AbstractEntity {

    function __construct($id = null) {
        $this->select = '
            SELECT
               permission.id AS id,
               keyid,
               doorkey.code AS keycode,
               doorperson.name AS keyholder,
               doorkeystatus.name AS keystatus,
               lockid,
               doorlock.sc AS lockcode,
               doorlock.name AS lockname,
               doorplace.name AS lockplace,
               allows,
               denies,
               permission.status AS statusid,
               permissionstatus.name AS statusname
        ';

        $this->from = '
            FROM permission
            LEFT JOIN permissionstatus ON (permission.status = permissionstatus.id)
            LEFT JOIN doorkey ON (permission.keyid = doorkey.id)
            LEFT JOIN doorperson ON (doorkey.holder = doorperson.id)
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
            LEFT JOIN doorlock ON (permission.lockid = doorlock.id)
            LEFT JOIN doorplace ON (doorlock.place = doorplace.id)
        ';

      $this->where = '
         WHERE permission.id = '.$id.'
      ';

        $this->order = '
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

   function getStatusId() {
      return $this->statusid;
   }

   function getStatus() {
      return $this->getStatusName();
   }

   function getStatusName(){
      return $this->statusname;
   }

   function getLockId(){
      return $this->lockid;
   }

   function getLockCode(){
      return $this->lockcode;
   }

   function getLockName(){
      return $this->lockname;
   }

   function getLockPlace(){
      return $this->lockplace;
   }

   function getKeyId(){
      return $this->keyid;
   }

   function getKeyCode(){
      return $this->keycode;
   }

   function getKeyHolder(){
      return $this->keyholder;
   }

   function getKeyOwner(){
      return $this->getKeyHolder();
   }

   function getKeyStatus(){
      return $this->keystatus;
   }

}
