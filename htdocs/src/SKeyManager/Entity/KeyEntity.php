<?php

namespace SKeyManager\Entity;

class KeyEntity extends AbstractEntity {

    protected $locationPattern = '/keys/show/%s';

    function __construct($id) {
        $this->select = '
            SELECT
                doorkey.id,
                code,
                doorkeystatus.name AS statusname,
                doorperson.name AS owner,
                doorkey.comment AS keycomment


               doorkey.id,
               elnumber,
               code,
               type,
               doorkeycolor.name AS colorname,
               doorkeystatus.name AS statusname,
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

         $this->where = '
            WHERE doorkey.id = ' . $id . '
         ';

        $this->order = '
            ORDER BY code
        ';
    }

}
