<?php

namespace SKeyManager\Repository;

class KeyRepository extends AbstractRepository {

    protected $locationPattern = '/key/%s';

    function __construct() {
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
               doorperson.name AS ownername,
               doorperson.id AS ownerid,
               doorperson.uid AS owneruid,
               doorkey.comment AS keycomment,
               communication,
               doorkey.lastupdate AS keyupdate
        ';

        $this->from = '
            FROM doorkey
            LEFT JOIN doorkeycolor ON (doorkey.color = doorkeycolor.id )
            LEFT JOIN doorkeystatus ON (doorkey.status = doorkeystatus.id)
            LEFT JOIN doorkeymech ON (doorkey.mech = doorkeymech.id )
            LEFT JOIN doorperson ON (doorkey.owner = doorperson.id )
        ';

        $this->order = '
            ORDER BY code
        ';
    }

   function load() {
      $data = $this->query();
      foreach($data as $name => $value){
         $this->$name = $value;
      }
   }

    function getByPersonId($id) {
        return $this->query('WHERE doorperson.id = '.$id, 'SKeyManager\Entity\Key');
    }

    function getByAllowedForLock($id) {
        return $this->query('WHERE doorperson.id = '.$id);
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Key');
    }
}
