<?php

namespace SKeyManager\Repository;

class PermissionRepository extends AbstractRepository {

    function __construct() {
        $this->select = '
            SELECT
               id
        ';

        $this->from = '
            FROM `permission`
        ';

        $this->where = '
        ';

        $this->order = '
        ';
    }

    function getByLockId($id) {
        return $this->query('WHERE `lockid` = '.$id, 'SKeyManager\Entity\Permission');
    }

    function getByKeyId($id) {
        return $this->query('WHERE `keyid` = '.$id, 'SKeyManager\Entity\Permission');
    }

    function getKeyAllowedOnLock($keyId, $lockId) {
         return $this->query('WHERE `keyid` = '.$keyId.' AND `lockid` = '.$lockId.' AND `allows` = TRUE ', 'SKeyManager\Entity\Permission');
    }

    function getLockDeniedForKey($lockId, $keyId) {
         return $this->query('WHERE `keyid` = '.$keyId.' AND `lockid` = '.$lockId.' AND `denies` = TRUE ', 'SKeyManager\Entity\Permission');
    }

    function getAllowedByKeyId($id) {
        return $this->query('WHERE `keyid` = '.$id.' AND `allows` = TRUE', 'SKeyManager\Entity\Permission');
    }

    function getDeniedByKeyId($id) {
        return $this->query('WHERE `keyid` = '.$id.' AND `denies` = TRUE', 'SKeyManager\Entity\Permission');
    }

    function getDeniesByLock($id) {
        return $this->query('WHERE `lockid` = '.$id.' AND `denies` = TRUE', 'SKeyManager\Entity\Permission');
    }

    function getAllowsByLock($id) {
        return $this->query('WHERE `lockid` = '.$id.' AND `allows` = TRUE', 'SKeyManager\Entity\Permission');
    }

    function setAllowPermission($keyId = 0, $lockId = 0, $mode = 0, $status = 0) {
        // error_log('Setting perm: keyid '.$keyId.' lockid '.$lockId.' mode '.$mode.' status '.$status.' on permrepo');
        $dbTable = 'permission';
        $conditions = array(
            'lockid' => $lockId,
            'keyid' => $keyId,
            'allows' => true
        );
        if($status == 0){
           return $this->deleteDb($dbTable, $conditions);
        } else {
           // Check if the permission already exists
           if($this->getKeyAllowedOnLock($keyId, $lockId)) {
               $con = openDb();
               return $this->updateDb($con, $dbTable, array('status' => $status), $conditions);
           } else {
               $conditions['status'] = $status;
               $con = openDb();
               return $this->insertDb($con, $dbTable, $conditions);
           }
        }
    }

    function setDenyPermission($lockId = 0, $keyId = 0, $status = 0) {
        $dbTable = 'permission';
        $conditions = array(
            'lockid' => $lockId,
            'keyid' => $keyId,
            'denies' => true
        );
        if($status == 0){
           return $this->deleteDb($dbTable, $conditions);
        } else {
           // Check if the permission already exists
           if($this->getLockDeniedForKey($lockId, $keyId)) {
               $con = openDb();
               return $this->updateDb($con, $dbTable, array('status' => $status), $conditions);
           } else {
               $conditions['status'] = $status;
               $con = openDb();
               return $this->insertDb($con, $dbTable, $conditions);
           }
        }
    }

    function getAll() {
        return $this->query('', 'SKeyManager\Entity\Permission');
    }
}
