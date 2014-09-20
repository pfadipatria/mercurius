<?php

namespace SKeyManager\Entity;

class PersonEntity extends AbstractEntity {

   protected $locationPattern = '/person/%s';

   function __construct($id) {
      $this->select = '
         SELECT
             id,
             name,
             uid,
             uidnumber,
             mdbid,
             comment,
             lastupdate
      ';

      $this->from = '
         FROM doorperson
      ';
      $this->where = '
         WHERE doorperson.id = '.$id.'
      ';
      $this->order = '
         ORDER BY name;
      ';

      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }
   }

   function getId() {
      return $this->id;
   }

   function getName() {
      return $this->name;
   }

}
