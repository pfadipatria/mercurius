<?php

namespace SKeyManager\Entity;

class Permission extends AbstractEntity {

    function __construct($id = null) {
        $this->select = '
            SELECT
               permission.id AS id,
               keyid,
               key.code AS keycode,
               person.name AS keyholder,
               keystatus.name AS keystatus,
               lockid,
               lock.sc AS lockcode,
               lock.name AS lockname,
               place.name AS lockplace,
               allows,
               denies,
               permission.status AS statusid,
               permissionstatus.name AS statusname
        ';

        $this->from = '
            FROM `permission`
            LEFT JOIN `permissionstatus` ON (permission.status = permissionstatus.id)
            LEFT JOIN `key` ON (permission.keyid = key.id)
            LEFT JOIN `person` ON (key.holder = person.id)
            LEFT JOIN `keystatus` ON (key.status = keystatus.id)
            LEFT JOIN `lock` ON (permission.lockid = lock.id)
            LEFT JOIN `place` ON (lock.place = place.id)
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

   function getSymbol(){

      if( $this->allows ) {
         switch ($this->statusid) {
         case 1:
            return '+?';
            break;
         case 2:
            return 'o';
            break;
         case 3:
            return '++';
            break;
         case 4:
            return '+-';
            break;
         }
      }

      if( $this->denies ) {
         switch ($this->statusid) {
         case 1:
            return '-?';
            break;
         case 2:
            return 'x';
            break;
         case 3:
            return '-+';
            break;
         case 4:
            return '--';
            break;
         }
      }

      return '';
   }

}
