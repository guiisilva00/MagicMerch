<?php

const SERVIDOR_BANCO_DADOS = 'localhost';
const NOME_BANCO_DADOS = 'magicmerch_db';
const USUARIO_BANCO_DADOS = 'root';
const SENHA_BANCO_DADOS = '';

function criarConexaoBancoDados(): PDO
{
    $fonteDados = 'mysql:host=' . SERVIDOR_BANCO_DADOS . ';dbname=' . NOME_BANCO_DADOS . ';charset=utf8mb4';

    return new PDO($fonteDados, USUARIO_BANCO_DADOS, SENHA_BANCO_DADOS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}