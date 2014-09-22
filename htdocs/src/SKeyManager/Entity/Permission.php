<?php

namespace SKeyManager\Entity;

class Permission extends AbstractEntity {

    function __construct($id = null) {
        $this->select = '
            SELECT
               permission.id AS id,
               keyid,
               doorkey.code AS keycode,
               doorperson.name AS keyowner,
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
            LEFT JOIN doorperson ON (doorkey.owner = doorperson.id)
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

   function getStatus() {
      return $this->getStatusName();
   }

   function getStatusName(){
      return $this->statusname;
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

}
