<?php
/**
 * Configuração da conexão MySQL usada durante o desenvolvimento local com XAMPP.
 * Altere estes dados somente se a sua instalação local usar credenciais diferentes.
 */
const SERVIDOR_BANCO_DADOS = 'localhost';
const NOME_BANCO_DADOS = 'magicmerch_db';
const USUARIO_BANCO_DADOS = 'root';
const SENHA_BANCO_DADOS = '';

/**
 * Abre a conexão PDO com o banco de dados da aplicação.
 *
 * @throws PDOException Quando o MySQL não está disponível ou o banco ainda não foi importado.
 */
function criarConexaoBancoDados(): PDO
{
    $fonteDados = 'mysql:host=' . SERVIDOR_BANCO_DADOS . ';dbname=' . NOME_BANCO_DADOS . ';charset=utf8mb4';

    return new PDO($fonteDados, USUARIO_BANCO_DADOS, SENHA_BANCO_DADOS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}