<?php

namespace SKeyManager\Entity;

class Permission extends AbstractEntity {

    function __construct($id = null) {
        $this->select = '
            SELECT
               permission.id AS id,
               key,
               lock,
               allows,
               denies,
               status AS statusid,
               permissionstatus.name AS statusname
        ';

        $this->from = '
            FROM permission
            LEFT JOIN permissionstatus ON (permission.status = permissionstatus.id)
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

}
