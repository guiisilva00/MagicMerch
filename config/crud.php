<?php

// Função auxiliar para proteger nomes de tabelas e colunas
function sanitizeIdentifier($identifier) {
    return '`' . str_replace('`', '``', $identifier) . '`';
}

// Inserir registro
function create($pdo, $table, array $data) {
    $table = sanitizeIdentifier($table);
    $columns = implode(', ', array_map('sanitizeIdentifier', array_keys($data)));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
    $stmt->execute(array_values($data));
    return $pdo->lastInsertId();
}

// Buscar múltiplos registros
function readAll($pdo, $table, $where = null, array $params = []) {
    $table = sanitizeIdentifier($table);
    $sql = "SELECT * FROM $table";
    if ($where) {
        $sql .= " WHERE $where";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Buscar apenas um registro
function read($pdo, $table, $where = null, array $params = []) {
    $table = sanitizeIdentifier($table);
    $sql = "SELECT * FROM $table";
    if ($where) {
        $sql .= " WHERE $where";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

// Atualizar registro
function update($pdo, $table, array $data, $where, array $params = []) {
    $table = sanitizeIdentifier($table);
    $set = implode(', ', array_map(fn($col) => sanitizeIdentifier($col) . " = ?", array_keys($data)));
    
    $stmt = $pdo->prepare("UPDATE $table SET $set WHERE $where");
    $stmt->execute(array_merge(array_values($data), $params));
    return $stmt->rowCount();
}

// Deletar registro
function delete($pdo, $table, $where, array $params = []) {
    $table = sanitizeIdentifier($table);
    $stmt = $pdo->prepare("DELETE FROM $table WHERE $where");
    return $stmt->execute($params);
}
?>
