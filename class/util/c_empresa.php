<?php

/**
 * @package   astec
 * @name      c_empresa
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admsistema.com.br/
 * @author    Joshua Silva
 * @date      14/07/2025
 */

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_database_pdo.php');
require_once($dir . "/../../bib/c_user.php");




class c_empresa extends c_user {
    private $empresa_id = '';
    private $nome_empresa = '';
    private $nome_fantasia = '';
    private $centro_custo = '';
    private $cnpj = '';

    public function setEmpresaId($empresa_id) { $this->empresa_id = $empresa_id; }
    public function getEmpresaId() { return $this->empresa_id; }

    public function setNomeEmpresa($nome_empresa) { $this->nome_empresa = $nome_empresa; }
    public function getNomeEmpresa() { return $this->nome_empresa; }

    public function setNomeFantasia($nome_fantasia) { $this->nome_fantasia = $nome_fantasia; }
    public function getNomeFantasia() { return $this->nome_fantasia; }

    public function setCentroCusto($centro_custo) { $this->centro_custo = $centro_custo; }
    public function getCentroCusto() { return $this->centro_custo; }

    public function setCnpj($cnpj) { $this->cnpj = $cnpj; }
    public function getCnpj() { return $this->cnpj; }

    /**
     * Seleciona uma empresa pelo código
     * @param string|int $empresa_id Código da empresa
     * @return array|null Dados da empresa ou null se não encontrada
     * @throws Exception Se o parâmetro for vazio
     */
    public function selecionaEmpresaPorId($empresa_id) {
        try {
            if (empty($empresa_id)) {
                throw new Exception('O código da empresa (empresa_id) não pode ser vazio.');
            }
            $sql = "SELECT 
                e.EMPRESA, 
                e.NOMEEMPRESA, 
                e.NOMEFANTASIA, 
                e.CENTROCUSTO, 
                e.CNPJ, 
                e.INSCESTADUAL, 
                e.CEP, 
                e.ENDERECO, 
                e.NUMERO, 
                e.COMPLEMENTO, 
                e.BAIRRO, 
                e.CIDADE, 
                e.UF, 
                e.CODMUNICIPIO, 
                e.EMAIL, 
                e.FONEAREA, 
                e.FONENUM, 
                e.REGIMETRIBUTARIO, 
                e.MSG_INFORMACAO_COMPLEMENTAR,
                f.CASASDECIMAIS
            FROM AMB_EMPRESA e
            LEFT JOIN FAT_PARAMETRO f ON f.FILIAL = e.CENTROCUSTO
            WHERE e.EMPRESA = ?";
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(1, $empresa_id);
            $banco->execute();
            $resultado = $banco->fetchAll();
            return $resultado ? $resultado[0] : null;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Seleciona todas as empresas cadastradas
     * @return array|false Lista de empresas ou false em caso de erro
     */
    public function selecionaTodasEmpresas() {
        try {
            $sql = "SELECT 
                    e.EMPRESA, 
                    e.NOMEEMPRESA, 
                    e.NOMEFANTASIA, 
                    e.CNPJ, 
                    e.CENTROCUSTO, 
                    e.REGIMETRIBUTARIO, 
                    e.MSG_INFORMACAO_COMPLEMENTAR,
                    f.CASASDECIMAIS
                FROM AMB_EMPRESA e
                LEFT JOIN FAT_PARAMETRO f ON f.FILIAL = e.CENTROCUSTO";
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Seleciona empresas filtrando por nome (LIKE)
     * @param array $filtros Array associativo com a chave 'nome_empresa'
     * @return array|false Lista de empresas filtradas ou false em caso de erro
     */
    public function selecionaEmpresasFiltradas($filtros) {
        try {
            $sql = "SELECT 
                    e.EMPRESA, 
                    e.NOMEEMPRESA, 
                    e.NOMEFANTASIA, 
                    e.CNPJ, 
                    e.CENTROCUSTO, 
                    e.REGIMETRIBUTARIO, 
                    e.MSG_INFORMACAO_COMPLEMENTAR,
                    f.CASASDECIMAIS
                FROM AMB_EMPRESA e
                LEFT JOIN FAT_PARAMETRO f ON f.FILIAL = e.CENTROCUSTO
                WHERE 1=1";
            $params = [];
            if (!empty($filtros['nome_empresa'])) {
                $sql .= " AND e.NOMEEMPRESA LIKE ?";
                $params[] = '%' . $filtros['nome_empresa'] . '%';
            }
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            foreach ($params as $i => $param) {
                $banco->bindValue($i + 1, $param);
            }
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Inclui uma nova empresa
     * @param array $dados_empresa Array associativo com todos os campos do cadastro de empresa (exceto EMPRESA/empresa_id)
     * @return string|false Último ID inserido ou false em caso de erro
     * @throws Exception Se algum campo obrigatório estiver vazio
     */
    public function incluiEmpresa($dados_empresa) {
        try {
            $camposObrigatorios = [
                'nome_empresa', 'nome_fantasia', 'cnpj',
                'inscricao_estadual', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'estado',
                'codigo_municipio', 'email', 'telefone', 'regime_tributario', 'casas_decimais'
            ];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados_empresa[$campo])) {
                    throw new Exception('O campo obrigatório "' . $campo . '" deve ser preenchido.');
                }
            }
            $centro_custo = $this->geraCentroCustoSequencial();

            // Separar DDD e número
            $telefone = preg_replace('/\D/', '', $dados_empresa['telefone']);
            $fonearea = substr($telefone, 0, 2);
            $fonenum = substr($telefone, 2);

            // Garantir que o CEP seja apenas números
            $cep = preg_replace('/\D/', '', $dados_empresa['cep']);

            $sql = "INSERT INTO AMB_EMPRESA (
                NOMEEMPRESA, NOMEFANTASIA, CENTROCUSTO, CNPJ, INSCESTADUAL, CEP, ENDERECO, NUMERO, COMPLEMENTO, BAIRRO, CIDADE, UF, CODMUNICIPIO, EMAIL, FONEAREA, FONENUM, REGIMETRIBUTARIO, MSG_INFORMACAO_COMPLEMENTAR
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(1, $dados_empresa['nome_empresa']);
            $banco->bindValue(2, $dados_empresa['nome_fantasia']);
            $banco->bindValue(3, $centro_custo);
            $banco->bindValue(4, $dados_empresa['cnpj']);
            $banco->bindValue(5, $dados_empresa['inscricao_estadual']);
            $banco->bindValue(6, $cep);
            $banco->bindValue(7, $dados_empresa['rua']);
            $banco->bindValue(8, $dados_empresa['numero']);
            $banco->bindValue(9, $dados_empresa['complemento']);
            $banco->bindValue(10, $dados_empresa['bairro']);
            $banco->bindValue(11, $dados_empresa['cidade']);
            $banco->bindValue(12, $dados_empresa['estado']);
            $banco->bindValue(13, $dados_empresa['codigo_municipio']);
            $banco->bindValue(14, $dados_empresa['email']);
            $banco->bindValue(15, $fonearea);
            $banco->bindValue(16, $fonenum);
            $banco->bindValue(17, $dados_empresa['regime_tributario']);
            $banco->bindValue(18, $dados_empresa['mensagem_complementar'] ?? null);
            $banco->execute();

            $lastId = $banco->lastInsertId();
            if ($lastId && is_numeric($lastId)) {
                // Inserir casas_decimais em FAT_PARAMETRO somente se a empresa foi inserida com sucesso
                $sqlFat = "INSERT INTO FAT_PARAMETRO (FILIAL, CASASDECIMAIS) VALUES (?, ?)";
                $banco->prepare($sqlFat);
                $banco->bindValue(1, $centro_custo);
                $banco->bindValue(2, $dados_empresa['casas_decimais']);
                $banco->execute();
            }

            return $lastId;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Altera os dados de uma empresa existente
     * @param string|int $empresa_id Código da empresa
     * @param array $dados_empresa Array associativo com todos os campos do cadastro de empresa (exceto centro_custo)
     * @return int|false Número de linhas afetadas ou false em caso de erro
     * @throws Exception Se algum campo obrigatório estiver vazio
     */
    public function alteraEmpresa($empresa_id, $dados_empresa) {
        try {
            if (empty($empresa_id)) {
                throw new Exception('Todos os campos obrigatórios devem ser preenchidos para alterar uma empresa.');
            }
            $camposObrigatorios = [
                'nome_empresa', 'nome_fantasia', 'cnpj',
                'inscricao_estadual', 'cep', 'rua', 'numero', 'bairro', 'cidade', 'estado',
                'codigo_municipio', 'email', 'telefone'
            ];
            foreach ($camposObrigatorios as $campo) {
                if (empty($dados_empresa[$campo])) {
                    throw new Exception('O campo obrigatório "' . $campo . '" deve ser preenchido.');
                }
            }

            // Separar DDD e número
            $telefone = preg_replace('/\D/', '', $dados_empresa['telefone']);
            $fonearea = substr($telefone, 0, 2);
            $fonenum = substr($telefone, 2);
            // Garantir que o CEP seja apenas números
            $cep = preg_replace('/\D/', '', $dados_empresa['cep']);

            $sql = "UPDATE AMB_EMPRESA SET 
                NOMEEMPRESA=?, NOMEFANTASIA=?, CNPJ=?, INSCESTADUAL=?, CEP=?, ENDERECO=?, NUMERO=?, COMPLEMENTO=?, BAIRRO=?, CIDADE=?, UF=?, CODMUNICIPIO=?, EMAIL=?, FONEAREA=?, FONENUM=?
                WHERE EMPRESA=?";
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(1, $dados_empresa['nome_empresa']);
            $banco->bindValue(2, $dados_empresa['nome_fantasia']);
            $banco->bindValue(3, $dados_empresa['cnpj']);
            $banco->bindValue(4, $dados_empresa['inscricao_estadual']);
            $banco->bindValue(5, $cep);
            $banco->bindValue(6, $dados_empresa['rua']);
            $banco->bindValue(7, $dados_empresa['numero']);
            $banco->bindValue(8, $dados_empresa['complemento']);
            $banco->bindValue(9, $dados_empresa['bairro']);
            $banco->bindValue(10, $dados_empresa['cidade']);
            $banco->bindValue(11, $dados_empresa['estado']);
            $banco->bindValue(12, $dados_empresa['codigo_municipio']);
            $banco->bindValue(13, $dados_empresa['email']);
            $banco->bindValue(14, $fonearea);
            $banco->bindValue(15, $fonenum);
            $banco->bindValue(16, $empresa_id);
            $banco->execute();
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Exclui uma empresa pelo código
     * @param string|int $empresa_id Código da empresa
     * @return int|false Número de linhas afetadas ou false em caso de erro
     * @throws Exception Se o parâmetro for vazio
     */
    public function excluiEmpresa($empresa_id) {
        try {
            if (empty($empresa_id)) {
                throw new Exception('O código da empresa (empresa_id) não pode ser vazio para exclusão.');
            }
            $sql = "DELETE FROM AMB_EMPRESA WHERE EMPRESA=?";
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(1, $empresa_id);
            $banco->execute();
            return $banco->rowCount();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Gera o próximo centro de custo sequencial para empresa
     * @return string Novo centro de custo
     */
    private function geraCentroCustoSequencial() {
        $banco = new c_banco_pdo();
        $sql = "SELECT MAX(CENTROCUSTO) as max_cc FROM AMB_EMPRESA";
        $banco->prepare($sql);
        $banco->execute();
        $res = $banco->fetchAll();
        $ultimo = isset($res[0]['max_cc']) ? (int)$res[0]['max_cc'] : 0;
        if ($ultimo < 10000000) {
            $novo = 10000000;
        } else {
            $novo = $ultimo + 10000000;
        }
        return strval($novo);
    }

    /**
     * Salva uma logo da empresa (nome fixo, só PNG, sobrescreve, TABLE = 'AMB_EMPRESA', caminho relativo)
     * @param array $dados [id_empresa, file]
     * @return array Resposta JSON formatada
     */
    public function salvarLogoEmpresa($dados)
    {
        try {
            $id_empresa = (int)$dados['id_empresa'];
            $file = $dados['file'] ?? null;
            if (!$file || $file['error'] !== 0) {
                throw new Exception('Nenhum arquivo enviado ou erro no upload.');
            }
            $nameFile = 'logo.png';
            $tmp_dir = $file['tmp_name'];
            $sizeFile = $file['size'];
            $anexoExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Só aceita PNG
            if ($anexoExt !== 'png') {
                throw new Exception('Apenas arquivos PNG são permitidos.');
            }
            if ($sizeFile > 2000000) {
                throw new Exception('O arquivo é muito grande. Tamanho máximo permitido: 2MB.');
            }

            // Diretório padrão por empresa
            $upload_dir = "/images/";
            $upload_dir_full = ADMraizCliente . $upload_dir;
            if (!file_exists($upload_dir_full)) {
                mkdir($upload_dir_full, 0755, true);
            }
            $path = $upload_dir_full . $nameFile; // Caminho físico
            $parts = explode('/', ADMraizCliente);
            $cliente = isset($parts[4]) ? $parts[4] : '';
            $path_db = '/' . $cliente . '/images/logo.png'; // Caminho web para o banco

            // Não exclui registros antigos, apenas insere novo e sobrescreve o arquivo físico
            $banco = new c_banco_pdo();
            $sql = "INSERT INTO AMB_GED (`TABLE`, `TABLE_ID`, `PATH`, `DESTAQUE`, `USER_INSERT`) VALUES ('AMB_EMPRESA', ?, ?, 'S', ?)";
            $banco->prepare($sql);
            $banco->bindValue(1, $id_empresa);
            $banco->bindValue(2, $path_db);
            $banco->bindValue(3, $this->m_userid ?? '');
            $banco->execute();
            $idLogo = $banco->lastInsertId();

            // Salva o arquivo físico na pasta images do cliente
            if (!move_uploaded_file($tmp_dir, $path)) {
                throw new Exception('Falha ao mover o arquivo para a pasta images.');
            }

            return [
                'success' => true,
                'id' => $idLogo,
                'caminho' => $path_db,
                'message' => 'Logo enviada com sucesso!'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lista as logos da empresa
     * @param int $id_empresa
     * @return array
     */
    public function selectLogoEmpresa($id_empresa)
    {
        try {
            $banco = new c_banco_pdo();
            $sql  = "SELECT `ID`, `TABLE`, `TABLE_ID`, `PATH`, SUBSTRING_INDEX(`PATH`, '.', -1) AS `EXTENSAO` ";
            $sql .= "FROM AMB_GED ";
            $sql .= "WHERE (`TABLE_ID` = ? ) AND (`TABLE` = 'AMB_EMPRESA')";
            $banco->prepare($sql);
            $banco->bindValue(1, $id_empresa);
            $banco->execute();
            $logos = $banco->fetchAll();

            $dadosFormatados = [];
            foreach ($logos as $logo) {
                $dadosFormatados[] = [
                    'id' => $logo['ID'],
                    'id_empresa' => $logo['TABLE_ID'],
                    'extensao' => strtolower($logo['EXTENSAO']),
                    'caminho_completo' => $logo['PATH']
                ];
            }

            return [
                'success' => true,
                'data' => $dadosFormatados
            ];
        } catch (Exception $e) {
            if (isset($banco)) {
            }
            return [
                'success' => false,
                'message' => 'Erro ao carregar logos: ' . $e->getMessage(),
                'sql' => $sql
            ];
        }
    }

    /**
     * Exclui uma logo da empresa
     * @param int $id_logo
     * @return bool
     */
    public function excluiLogoEmpresa($id_logo)
    {
        $banco = new c_banco_pdo();
        $sql = "SELECT PATH FROM AMB_GED WHERE ID = ? AND `TABLE` = 'AMB_EMPRESA'";
        $banco->prepare($sql);
        $banco->bindValue(1, $id_logo);
        $banco->execute();
        $row = $banco->fetch();
        if ($row && isset($row['PATH'])) {
            $file = ADMraizCliente . $row['PATH'];
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $banco = new c_banco_pdo();
        $sql = "DELETE FROM AMB_GED WHERE ID = ? AND `TABLE` = 'AMB_EMPRESA'";
        $banco->prepare($sql);
        $banco->bindValue(1, $id_logo);
        $banco->execute();
        $status = $banco->rowCount() > 0;
        return $status;
    }

    /**
     * Grava registro de logo da empresa no banco
     * @param int $id_empresa
     * @param string $path
     * @param string $destaque
     * @return int|false
     */
    public function gravaLogoEmpresa($id_empresa, $path, $destaque = 'N')
    {
        $banco = new c_banco_pdo();
        $sql = "INSERT INTO AMB_GED (`TABLE`, `TABLE_ID`, `PATH`, `DESTAQUE`, `USER_INSERT`) VALUES ('AMB_EMPRESA', ?, ?, ?, ?)";
        $banco->prepare($sql);
        $banco->bindValue(1, $id_empresa);
        $banco->bindValue(2, $path);
        $banco->bindValue(3, $destaque);
        $banco->bindValue(4, $this->m_userid ?? '');
        $banco->execute();
        $id = $banco->lastInsertId();
        return $id ? (int)$id : false;
    }
}
