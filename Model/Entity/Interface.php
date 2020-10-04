<?php

namespace Model\Entity;

interface Entity {
    public static function insert($data);

    public static function update($data, $id);

    public static function select($data, $limit);

    public static function delete($data, $id);
}