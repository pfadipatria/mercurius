<?php

namespace SKeyManager\Entity;

class KeyEntity extends AbstractEntity {

    protected $locationPattern = '/key/%s';

    function __construct($id) {
        $this->id = $id;

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
               doorkeymech.bezeichung AS bezeichung,
               doorperson.name AS owner,
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

}
