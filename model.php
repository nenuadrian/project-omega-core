<?php

abstract class Model {
    protected static string $table;
    protected static string $identityColumn;

    public static function insert(array $data): int {
        DB::insert(static::$table, $data);
        return DB::insertId();
    }

    public static function update(int $id, $data): void {
        DB::update(static::$table, $data, static::$identityColumn . "=%d", $id);
    }

    public static function byId(int $id): ?array {
        $data = DB::queryFirstRow("SELECT * FROM " . static::$table . " WHERE " . static::$identityColumn . " = %d AND deleted_at is null", $id);
        if ($data) {
            if (isset($data['password'])) {
                unset($data['password']);
            }
        } 
        return $data;
    }

    public static function deleteById(int $id): void {
        DB::query("UPDATE " . static::$table . " SET deleted_at = CURRENT_TIMESTAMP() WHERE " . static::$identityColumn . " = %d LIMIT 1", $id);
    }

    public static function all(): array {
        return DB::query("SELECT * FROM " . static::$table . " WHERE deleted_at is null");
    }

    public static function byUserId(int $userId): ?array {
        return DB::query("SELECT * FROM " . static::$table . " WHERE user_id = %d AND deleted_at is null", $userId);
    }
}
