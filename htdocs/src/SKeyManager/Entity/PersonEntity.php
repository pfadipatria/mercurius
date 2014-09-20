<?php

namespace SKeyManager\Entity;

class PersonEntity extends AbstractEntity {

   protected $locationPattern = '/person/show/%s';
   var $id;

   function __construct($id) {
      error_log('id ist ' . $id)
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

      $this->where = '
        WHERE id = ' . $id . '
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

   function getKeys() {

   }

}
