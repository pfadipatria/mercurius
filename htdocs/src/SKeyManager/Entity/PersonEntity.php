<?php

namespace SKeyManager\Entity;

class PersonEntity extends AbstractEntity {

   protected $locationPattern = '/person/show/%s';

   function __construct($id) {
      $this->id = $id;

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

      $this->order = '
         ORDER BY name;
      ';
   }

   function getName() {
      $this->select = 'SELECT name';
      $row = parent::getAll();
      return $row['name'];
   }

}
