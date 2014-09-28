<?php

namespace SKeyManager\Repository;

class SearchRepository extends AbstractRepository {

   function getKeys($query) {
      $keys = new \SKeyManager\Repository\KeyRepository;
      $keys->select = '
         SELECT 
            key.id,
            key.code,
            key.color AS colorid,
            keycolor.name AS colorname,
            key.status AS statusid,
            keystatus.name AS statusname,
            holder AS holderid,
            person.name AS holdername,
            person.uid AS holderuid,
            key.comment AS comment
      ';
      $keys->from = '
         FROM `key`
         LEFT JOIN `keycolor` ON (key.color = keycolor.id)
         LEFT JOIN `keystatus` ON (key.status = keystatus.id)
         LEFT JOIN `keymech` ON (key.mech = keymech.id)
         LEFT JOIN `person` ON (key.holder = person.id)
      ';
      $keys->where = '
         WHERE
            key.code like "%'.$query.'%" OR
            keycolor.name like "%'.$query.'%" OR
            keystatus.name like "%'.$query.'%" OR
            person.name like "%'.$query.'%" OR
            key.comment like "%'.$query.'%"
      ';
      return $keys->getAll();
   }

   function getLocks($query) {
      $locks = new \SKeyManager\Repository\LockRepository;

      $locks->select = '
         SELECT
            lock.id AS id,
            number,
            lock.name AS name,
            sc AS code,
            comment,
            place.name AS venuename,
            place.id AS venueid,
            lock.status AS statusid,
            lockstatus.name AS statusname
      ';

      $locks->from = '
         FROM `lock`
         LEFT JOIN `place` ON (lock.place = place.id)
         LEFT JOIN `lockstatus` ON (lock.status = lockstatus.id)
      ';
      $locks->where = '
         WHERE
            number like "%'.$query.'%" OR
            lock.name like "%'.$query.'%" OR
            sc like "%'.$query.'%" OR
            place.name like "%'.$query.'%" OR
            lockstatus.name like "%'.$query.'%" OR
            comment like "%'.$query.'%"
      ';
      return $locks->getAll();
   }

   function getPeople($query) {
      $people = new \SKeyManager\Repository\PersonRepository;
      $condition = '
         WHERE
            name like "%'.$query.'%" OR
            uid like "%'.$query.'%" OR
            comment like "%'.$query.'%"
      ';
      return $people->getSubSet($condition);
   }


}
