<?php
// Time zone
setlocale(LC_TIME, 'pt_BR.utf8');
date_default_timezone_set('America/Sao_Paulo');
//
//API_TOKEN
if (!defined('APITOKEN'))
	define('APITOKEN', '2OCSA1GRAFEQVRRVPCKA');
//
//API_TOKEN
if (!defined('APIKEY'))
	define('APIKEY', 'AnZ0Ie0IcvjPAaEGeTw4SpTMMLlhyOr0K1aLcORWR0zwU6nGpS4kow2ewSwB9U8w9eAwFVPkSO4lg34nzngA');
//
/** caminho no server para o sistema **/
if (!defined('PAINELURL'))
	define('PAINELURL', 'https://painel.connectzap.es');
//
/** caminho no server para o sistema **/
if (!defined('APIURL'))
	define('APIURL', 'https://api.connectzap.com.br');
//
if (!defined('APIURLBND'))
	define('APIURLBND', 'https://apiwasrv.connectzap.es');
//
if (!defined('WEBHOOKURL'))
	define('WEBHOOKURL', 'https://webhook.connectzap.com.br');
//
/** pasta absoluta do sistema **/
if (!defined('ABSPAST'))
	define('ABSPAST', basename(__DIR__));
//
/** caminho absoluto para a pasta do sistema **/
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');
//
/** caminho do arquivo de banco de dados **/
if (!defined('DBAPI'))
	define('DBAPI', ABSPATH . 'inc/Conexao.php');
//
