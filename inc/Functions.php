<?php
//
// http://www.devwilliam.com.br/php/crud-no-php-com-pdo-e-mysql
// http://bootboxjs.com/
require_once "./config.php";
require_once DBAPI;
//
function formatarDocumento($numero)
{
	// Remove caracteres não numéricos
	$numero = preg_replace('/\D/', '', $numero);

	// Verifica se o número tem 11 dígitos (CPF), 14 dígitos (CNPJ) ou 9 dígitos (RG)
	if (strlen($numero) == 11) {
		// Formata como CPF: 000.000.000-00
		return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $numero);
	} elseif (strlen($numero) == 14) {
		// Formata como CNPJ: 00.000.000/0000-00
		return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $numero);
	} elseif (strlen($numero) == 9) {
		// Formata como RG: 00.000.000-0
		return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{1})/', '$1.$2.$3-$4', $numero);
	} else {
		// Retorna o número original se não for CPF, CNPJ nem RG válido
		return $numero;
	}
}
//
function isMobileNumber($number)
{
    // Remove todos os caracteres não numéricos
    $cleanedNumber = preg_replace('/\D/', '', $number);

    // Verifica se o número tem um formato válido para um número móvel
    // Formatos aceitos: 9XXXXXXXX, DDD9XXXXXXXX, DDIDDD9XXXXXXXX
    return preg_match('/^(\d{2,3})?(\d{2})?9\d{8}$/', $cleanedNumber);
}
//
function formatarTelefone($numero)
{
	// Remove caracteres especiais
	$numeroLimpo = preg_replace('/[^0-9]/', '', $numero);

	if (isMobileNumber($numeroLimpo)) {
		// Verifica se já possui o código do país, se não, adiciona
		if (substr($numeroLimpo, 0, 2) !== "55") {
			$numeroLimpo = "55" . $numeroLimpo;
		}

		// Formata baseado na quantidade de dígitos (assumindo já com o '55')
		// Celulares brasileiros têm 13 dígitos com o '55' + DDD + 9 + 8 dígitos do número
		// Telefones fixos têm 12 dígitos com o '55' + DDD + 8 dígitos do número
		if (strlen($numeroLimpo) === 13) {
			// Celular
			$formatado = '+' . substr($numeroLimpo, 0, 2) . ' (' . substr($numeroLimpo, 2, 2) . ') ' . substr($numeroLimpo, 4, 4) . '-' . substr($numeroLimpo, 8);
		} elseif (strlen($numeroLimpo) === 12) {
			// Celular add 9 digito
			$formatado = '+' . substr($numeroLimpo, 0, 2) . ' (' . substr($numeroLimpo, 2, 2) . ') 9 ' . substr($numeroLimpo, 4, 4) . '-' . substr($numeroLimpo, 8);
		} else {
			// Retorna o número sem formatação se não atender aos critérios acima
			$formatado = $numero;
		}
	} else {
		// Verifica se já possui o código do país, se não, adiciona
		if (substr($numeroLimpo, 0, 2) !== "55") {
			$numeroLimpo = "55" . $numeroLimpo;
		}
		// Fixo
		$formatado = '+' . substr($numeroLimpo, 0, 2) . ' (' . substr($numeroLimpo, 2, 2) . ') ' . substr($numeroLimpo, 4, 4) . '-' . substr($numeroLimpo, 8);
	}

	return $formatado;
}
//
function dateEmMysql($dateSql)
{
	$ano = substr($dateSql, 6);
	$mes = substr($dateSql, 3, -5);
	$dia = substr($dateSql, 0, -8);
	return $ano . "-" . $mes . "-" . $dia;
}
//
/*
 * Classe para operações CRUD na tabela ARTIGO
 */
class Data
{
	/*
     * Atributo para conexão com o banco de dados
     */
	private $pdo = null;

	/*
     * Atributo estático para instância da própria classe
     */
	private static $crudAdv = null;

	/*
     * Atributo estático para instância da tabela
     */
	private static $strTabela = null;

	/*
     * Construtor da classe como private
     * @param $conexao - Conexão com o banco de dados
     */
	private function __construct($conexao)
	{
		$this->pdo = $conexao;
	}

	public static function conectar($conexao)
	{
		if (!isset(self::$crudAdv)) :
			self::$crudAdv = new Data($conexao);
		endif;
		return self::$crudAdv;
	}
	//
	public function createTokenTeste(
		$newtoken,
		$nome,
		$cpfcnpj,
		$email,
		$senha,
		$senha_hash,
		$celular,
		$perfil,
		$active,
		$datainicial,
		$datafinal,
		$created,
		$modified
	) {
		if (
			!empty($newtoken) &&
			!empty($nome) &&
			!empty($email) &&
			!empty($senha_hash) &&
			!empty($celular)
		) {
			//
			try {
				$sql = "INSERT INTO usuarios (nome, cpfcnpj, email, senha, celular,  perfil, active, created, uptaded) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
				//print $sql;
				$stm = $this->pdo->prepare($sql);
				$stm->bindValue(1, $nome);
				$stm->bindValue(2, formatarDocumento($cpfcnpj));
				$stm->bindValue(3, $email);
				$stm->bindValue(4, $senha_hash);
				$stm->bindValue(5, $celular);
				$stm->bindValue(6, 'usr');
				$stm->bindValue(7, true);
				$stm->bindValue(8, $created);
				$stm->bindValue(9, $modified);
				$stm->execute();
				$newIdUser = $this->pdo->lastInsertId();
				//
				if (isset($newIdUser) && !empty($newIdUser)) {

					try {
						$sql = "INSERT INTO tokens (iduser, token, datainicial, datafinal, emailcob, tptoken, valor) VALUES (?, ?, ?, ?, ?, ?, ?)";
						//print $sql;
						$stm = $this->pdo->prepare($sql);
						$stm->bindValue(1, $newIdUser);
						$stm->bindValue(2, $newtoken);
						$stm->bindValue(3, $datainicial);
						$stm->bindValue(4, $datafinal);
						$stm->bindValue(5, $email);
						$stm->bindValue(6, "0");
						$stm->bindValue(7, "149.99");
						$stm->execute();
						$newIdToken = $this->pdo->lastInsertId();
						//
						return $newIdToken;
						//
					} catch (PDOException $erro) {
						//
						$sql = "DELETE FROM usuarios WHERE ID=?";
						//print $sql;
						$stm = $this->pdo->prepare($sql);
						$stm->bindValue(1, $newIdUser);
						$stm->execute();
						//
						return false;
						//
					}
				}
			} catch (PDOException $erro) {
				//
				return false;
				//
			}
		} else {
			//
			$file = "./send-filds-logs.txt";
			$text = "Erro; Campos vazios \r\n";
			$fp = fopen($file, "wb");
			fwrite($fp, $text);
			fclose($fp);
			//
			return false;
			//
		}
	}
	//
	public function createTokenTesteCnpj(
		$newtoken,
		$nome,
		$cpfcnpj,
		$cep,
		$uf,
		$cidade,
		$rua,
		$numero,
		$bairro,
		$complemento,
		$email,
		$senha,
		$senha_hash,
		$celular,
		$perfil,
		$active,
		$datainicial,
		$datafinal,
		$created,
		$modified
	) {
		if (
			!empty($newtoken) &&
			!empty($nome) &&
			!empty($email) &&
			!empty($senha_hash) &&
			!empty($celular)
		) {
			try {
				$sql = "INSERT INTO usuarios (nome, cpfcnpj, cep, uf, cidade, rua, numero, bairro, complemento, email, senha, celular, perfil, active, created, uptaded) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				//print $sql;
				$stm = $this->pdo->prepare($sql);
				$stm->bindValue(1, $nome);
				$stm->bindValue(2, formatarDocumento($cpfcnpj));
				$stm->bindValue(3, $cep);
				$stm->bindValue(4, $uf);
				$stm->bindValue(5, $cidade);
				$stm->bindValue(6, $rua);
				$stm->bindValue(7, $numero);
				$stm->bindValue(8, $bairro);
				$stm->bindValue(9, $complemento);
				$stm->bindValue(10, $email);
				$stm->bindValue(11, $senha_hash);
				$stm->bindValue(12, $celular);
				$stm->bindValue(13, 'usr');
				$stm->bindValue(14, true);
				$stm->bindValue(15, $created);
				$stm->bindValue(16, $modified);
				$stm->execute();
				$newIdUser = $this->pdo->lastInsertId();
				//
				if (isset($newIdUser) && !empty($newIdUser)) {

					try {
						$sql = "INSERT INTO tokens (iduser, token, datainicial, datafinal, emailcob, tptoken, valor) VALUES (?, ?, ?, ?, ?, ?, ?)";
						//print $sql;
						$stm = $this->pdo->prepare($sql);
						$stm->bindValue(1, $newIdUser);
						$stm->bindValue(2, $newtoken);
						$stm->bindValue(3, $datainicial);
						$stm->bindValue(4, $datafinal);
						$stm->bindValue(5, $email);
						$stm->bindValue(6, "0");
						$stm->bindValue(7, "149.99");
						$stm->execute();
						$newIdToken = $this->pdo->lastInsertId();
						//
						return $newIdToken;
						//
					} catch (PDOException $erro) {
						//
						$sql = "DELETE FROM usuarios WHERE ID=?";
						//print $sql;
						$stm = $this->pdo->prepare($sql);
						$stm->bindValue(1, $newIdUser);
						$stm->execute();
						//
						return false;
						//
					}
				}
			} catch (PDOException $erro) {
				//
				return false;
				//
			}
		} else {
			//
			return false;
			//
		}
	}
	//
	public function getCountUsuariosCpfcnpj($cpfcnpj = null)
	{
		try {
			$sql = "SELECT * FROM usuarios WHERE cpfcnpj = ?";
			//print $sql;
			$stm = $this->pdo->prepare($sql);
			$stm->bindValue(1, formatarDocumento($cpfcnpj));
			$stm->execute();
			$linha = $stm->rowCount();
			return $linha;
		} catch (PDOException $erro) {
			//echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>"; 
		}
	}
	//
	public function getCountUsuariosEmail($email = null)
	{
		try {
			$sql = "SELECT * FROM usuarios WHERE email = ?";
			//print $sql;
			$stm = $this->pdo->prepare($sql);
			$stm->bindValue(1, $email);
			$stm->execute();
			$linha = $stm->rowCount();
			return $linha;
		} catch (PDOException $erro) {
			//echo "<script>alert('Erro na linha: {$erro->getLine()}')</script>";
		}
	}
	//
}
