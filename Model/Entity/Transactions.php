<?php

namespace Model\Entity;
use Database\Sqlite;

class Transactions implements Entity {
    public static function insert($data) {
        foreach($data as $key => $value) {
            $keys[] = "`$key`";
            $values[] = '?';
            $execute[] = $value;
        }

        $keys = implode(', ', $keys);
        $values = implode(', ', $values);

        $db = Sqlite::connect();
        $stmt = $db->prepare("INSERT INTO `transactions` ($keys) VALUES ($values)");
        $stmt->execute($execute);
        return $db->lastInsertId();
    }

    public static function update($data, $id) {
        foreach($data as $key => $value) {
            $values[] = "`$key` = ?";
            $execute[] = $value;
        }

        $execute[] = $id;
        $values = implode(", ", $values);

        $db = Sqlite::connect();
        $stmt = $db->prepare("UPDATE `transactions` SET $values WHERE `id` = ?");
        $stmt->execute($execute);
    }

    public static function select($data, $limit) {
        $where = null;

        foreach($data as $key => $value) {
            if(empty($where)) {
                $where = "$key = ?"; 
            } else {
                $where .= "AND $key = ?"; 
            }

            $execute[] = $value;
        }

        $db = Sqlite::connect();
        $stmt = $db->prepare("SELECT * FROM `transactions` WHERE $where LIMIT $limit");
        $stmt->execute($execute);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function delete($data, $id) {
        
    }
}