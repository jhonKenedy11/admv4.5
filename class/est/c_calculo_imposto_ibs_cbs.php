<?php

/**
 * @package   astec
 * @name      c_ipm_estrategy_xml
 * @version   4.5.0
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy Dos Santos Mello <jhon.kened11@hotmail.com>
 * @date     17/12/2025
 */

include_once(__DIR__ . "/../../bib/c_database_pdo.php");

Class c_calculo_imposto_ibs_cbs {

    //atributos
    public $parm_post = array();
    public $parm_session = array();



    /**
     * Função para calcular o imposto IBS/CBS
     * @param array $dados [
     *      'id_nat_trib_ibs_cbs' => id_nat_trib_ibs_cbs,
     *      'id_c_class_trib' => id_c_class_trib,
     *      'valor_produto' => vProd,
     *      'valor_servico' => vServ,
     *      'valor_frete' => vFrete,
     *      'valor_seguro' => vSeg,
     *      'valor_outro' => vOutro,
     *      'valor_ii' => vII,
     *      'valor_desc' => vDesc,
     *      'valor_pis' => vPIS,
     *      'valor_cofins' => vCOFINS,
     *      'valor_icms' => vICMS,
     *      'valor_icms_uf_dest' => vICMSUFDest,
     *      'valor_fcp' => vFCP,
     *      'valor_fcp_uf_dest' => vFCPUFDest,
     *      'valor_icms_mono' => vICMSMono,
     *      'valor_issqn' => vISSQN,
     *      'id_natureza_operacao' => id_natureza_operacao,
     *      'uf_dest' => uf_dest,
     *      'mun_dest' => mun_dest,
     *      'pessoa' => pessoa,
     *      'cclasstrib' => cclasstrib,
     *      'ncm' => ncm,
     * ];
     *
     * @return array
     */
    function calculaImpostoIbsCbs($dados) {
        try {

            /*
                Exceção 1: Não subtrair o valor do PIS por Substituição Tributária (PIST/vPIS) quando
                compor o valor total da NF-e (se indSomaPISST=1);
                Exceção 2: Não subtrair o valor do COFINS por Substituição Tributária
                (COFINSST/vCOFINS) quando compor o valor total da NF-e (se
                indSomaCOFINSST=1).
                Nota: Implementação Futura
            */

            // Obtém as informações totais da tabela EST_C_CLASS_TRIB
            $est_c_class_trib = $this->getEstCClassTrib($dados['id_c_class_trib']);

            // Define as chaves para separar as informações da tabela EST_C_CLASS_TRIB
            $infos_keys = [
                'CST',
                'CCLASSTRIB',
                'TIPO_ALIQUOTA',
                'PRED_IBS',
                'PRED_CBS'
            ];

            // Separa as informações da tabela EST_C_CLASS_TRIB
            $c_class_trib_infos = array_intersect_key(
                $est_c_class_trib,
                array_flip($infos_keys)
            );

            // Separa as flags da tabela EST_C_CLASS_TRIB 
            $flags_c_class = array_diff_key(
                $est_c_class_trib,
                array_flip($infos_keys)
            );

            // Limpa as variáveis
            unset($est_c_class_trib);
            unset($infos_keys);

            // Valida as flags da tabela EST_C_CLASS_TRIB
            $flags_class_trib = $this->validateFlagsCClassTrib($flags_c_class);

            // Valida as flags da tabela EST_C_CST_IBS_CBS
            $flags_cst = $this->validateFlagsCst($c_class_trib_infos['CST']);



            // Natureza Tributo IBS/CBS
            $dados_natureza_tributo = [
                'id_natureza_operacao' => $dados['id_natureza_operacao'],
                'uf_dest' => $dados['uf_dest'],
                'mun_dest' => $dados['mun_dest'],
                'pessoa' => $dados['pessoa'],
                'cclasstrib' => $c_class_trib_infos['CCLASSTRIB'],
                'ncm' => $dados['ncm'],
            ];

            $tributos = $this->getEstNaturezaOperacaoTributoIbsCbs($dados_natureza_tributo);

            // Aliquota IBS Municipal
            $aliquota_ibs_municipal = floatval($tributos['ALIQUOTA_IBS_MUN'] ?? 0);
            $aliquota_ibs_estadual  = floatval($tributos['ALIQUOTA_IBS_UF'] ?? 0);
            $aliquota_cbs           = floatval($tributos['ALIQUOTA_CBS'] ?? 0);


            // 1. FORMAÇÃO DA BASE DE CÁLCULO (Regra UB16-10)
            // ATENÇÃO: Se indSomaPISST=1 ou indSomaCOFINSST=1, PIS/COFINS-ST não são deduzidos (Implementação Futura)
            $vSomas = ($dados['valor_produto'] ?? 0) +
                      ($dados['valor_servico'] ?? 0) +
                      ($dados['valor_frete']   ?? 0) +
                      ($dados['valor_seguro']  ?? 0) +
                      ($dados['valor_outro']   ?? 0) +
                      ($dados['valor_ii']      ?? 0);

            $vDeducoes = ($dados['valor_desc']         ?? 0) +
                         ($dados['valor_pis']          ?? 0) +
                         ($dados['valor_cofins']       ?? 0) +
                         ($dados['valor_icms']         ?? 0) +
                         ($dados['valor_icms_uf_dest'] ?? 0) +
                         ($dados['valor_fcp']          ?? 0) +
                         ($dados['valor_fcp_uf_dest']  ?? 0) +
                         ($dados['valor_icms_mono']    ?? 0) +
                         ($dados['valor_issqn']        ?? 0);

            $vBC = round($vSomas - $vDeducoes, 2);

            // 2. ALÍQUOTAS DE TRANSIÇÃO 2025/2026 (Regras UB18-10, UB37-10, UB56-10)
            $pIBSUF  = 0.1;
            $pIBSMun = 0.0;
            $pCBS    = 0.9;

            // 3. CÁLCULO DOS TRIBUTOS com dedução de Diferimentos
            // IBS Estadual (Regra UB35-10)
            $vDifUF     = $dados['valor_dif_uf']      ?? 0.0;
            $vDevTribUF = $dados['valor_dev_trib_uf'] ?? 0.0;
            $vIBSUF     = round(($vBC * ($pIBSUF / 100)) - $vDifUF - $vDevTribUF, 2);

            // IBS Municipal (Regra UB54-10)
            $vDifMun     = $dados['valor_dif_mun']      ?? 0.0;
            $vDevTribMun = $dados['valor_dev_trib_mun'] ?? 0.0;
            $vIBSMun     = round(($vBC * ($pIBSMun / 100)) - $vDifMun - $vDevTribMun, 2);

            // Total IBS do Item (Regra UB54a-10)
            $vCredPresIBS = $dados['valor_cred_pres_ibs'] ?? 0.0;
            $vIBS         = round($vIBSUF + $vIBSMun - $vCredPresIBS, 2);

            // CBS (Regra UB67-10)
            $vDifCBS     = $dados['valor_dif_cbs']      ?? 0.0;
            $vDevTribCBS = $dados['valor_dev_trib_cbs'] ?? 0.0;
            $vCBS        = round(($vBC * ($pCBS / 100)) - $vDifCBS - $vDevTribCBS, 2);

            // 4. RETORNO — chaves legadas (consumidas em p_nfephp_40.php) + chaves NF-e 4.0
            return [
                // Chaves legadas — compatibilidade com p_nfephp_40.php
                'flags_class_trib'       => $flags_class_trib,
                'flags_cst'              => $flags_cst,
                'c_class_trib'           => $c_class_trib_infos,
                'valor_bc'               => $vBC,
                'valor_ibs_municipal'    => $vIBSMun,
                'valor_ibs_estadual'     => $vIBSUF,
                'valor_cbs'              => $vCBS,
                'aliquota_ibs_municipal' => $pIBSMun,
                'aliquota_ibs_estadual'  => $pIBSUF,
                'aliquota_cbs'           => $pCBS,
                // Chaves NF-e 4.0 (nomenclatura do schema)
                'status'  => true,
                'vBC'     => $vBC,
                'pIBSUF'  => $pIBSUF,
                'vIBSUF'  => $vIBSUF,
                'pIBSMun' => $pIBSMun,
                'vIBSMun' => $vIBSMun,
                'vIBS'    => $vIBS,
                'pCBS'    => $pCBS,
                'vCBS'    => $vCBS,
            ];

        } catch (Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }


    /**
     * Função para validar as flags da tabela EST_C_CST_IBS_CBS
     * @param int $id_cst
     * @return array
     */
    function validateFlagsCst($id_cst): array 
    {
        $params_cst = $this->getCstIbscbs($id_cst);



        
        // Mapagem das tags obrigatórias
        $mapaTags = [
            'IND_G_IBS_CBS'          => 'gIBSCBS',
            'IND_G_IBS_CBS_MONO'     => 'gIBSCBSMono',
            'IND_G_RED'              => 'gRed',
            'IND_G_DIF'              => 'gDif',
            'IND_G_TRANSF_CRED'      => 'gTransfCred',
            'IND_G_CRED_PRES_IBSZFM' => 'gCredPresIBSZFM',
            'IND_G_AJUSTE_COMPET'    => 'gAjusteCompet',
            'IND_REDUTOR_BC'         => 'gCompraGov',
        ];
    
        $tags = [];
    
        foreach ($mapaTags as $coluna => $tag) 
        {
            if ((int)$params_cst[$coluna] === 1) 
            {
                $tags[] = $tag;
            }
        }
    
        return $tags;
    }

    /**
     * Função para validar as flags da tabela EST_C_CLASS_TRIB
     * @param array $flags
     * @return array
     */
    function validateFlagsCClassTrib($flags): array 
    { 
        // Mapagem das tags obrigatórias
        $mapaTags = [
            'IND_G_TRIB_REGULAR' => [
                "grupo" => "gTribRegular", 
                "campos" => [
                    "CSTReg", 
                    "cClassTribReg", 
                    "pAliqEfetRegIBSUF",
                    "vTribRegIBSUF",
                    "pAliqEfetRegIBSMun",
                    "vTribRegIBSMun",
                    "pAliqEfetRegCBS",
                    "vTribRegCBS"
                ]
            ],

            'IND_G_CRED_PRES_OPER' => [
                "grupo" => "gCredPresOper", 
                "campo" => [
                    "vBCCredPres",
                    "cCredPres"
                ]
            ],

            'IND_G_MONO_PADRAO' => [
                "grupo" => "gMonoPadrao", 
                "campo" => [
                    "qBCMono",
                    "adRemIBS",
                    "adRemCBS",
                    "vIBSMono",
                    "vCBSMono"
                ]
            ],

            'IND_G_MONO_RETEN' => [
                "grupo" => "gMonoReten", 
                "campo" => [
                    "qBCMonoReten",
                    "adRemIBSReten",
                    "vIBSMonoReten",
                    "adRemCBSReten",
                    "vCBSMonoReten"
                ]
            ],

            'IND_G_MONO_RET' => [
                "grupo" => "gMonoRet", 
                "campo" => [
                    "qBCMonoRet",
                    "adRemIBSRet",
                    "vIBSMonoRet",
                    "adRemCBSRet",
                    "vCBSMonoRet"
                ]
            ],

            // Descontinuado 
            // 'IND_G_MONO_DIF' => [
            //     "grupo" => "gMonoDif", 
            //     "campo" => [
            //         "pDifIBS",
            //         "vIBSMonoDif",
            //         "pDifCBS",
            //         "vTotIBSMonoItem",
            //         "vTotCBSMonoItem"
            //     ]
            // ],

            'IND_G_ESTORNO_CRED' => [
                "grupo" => "gEstornoCred", 
                "campo" => [
                    "vIBSEstCred",
                    "vCBSEstCred"
                ]
            ]
        ];

        $tags = [];
        
        foreach ($mapaTags as $coluna => $tag) {
            if ((int)$flags[$coluna] === 1) {
                $tags[] = $tag['grupo'];
            }
        }

        return $tags;
    }
    




    /**
     * Função para obter os dados do tributo IBS/CBS
     * @param array $dados
     * @return array
     */
    function getEstNaturezaOperacaoTributoIbsCbs($dados)
    {
        $banco = new c_banco_pdo();
    
        $sql = "SELECT * FROM EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS WHERE 1=1";
        $params = [];

        if (!empty($dados['uf_dest'])) {
            $sql .= " AND UF_DEST = :uf_dest";
            $params[':uf_dest'] = [$dados['uf_dest'], PDO::PARAM_STR];
        }

        // if (!empty($dados['mun_dest'])) {
        //     $sql .= " AND MUN_DEST = :mun_dest";
        //     $params[':mun_dest'] = [$dados['mun_dest'], PDO::PARAM_STR];
        // }

        if (!empty($dados['pessoa'])) {
            $sql .= " AND TIPO_PESSOA = :pessoa";
            $params[':pessoa'] = [$dados['pessoa'], PDO::PARAM_STR];
        }

        if (!empty($dados['cclasstrib'])) {
            $sql .= " AND CCLASSTRIB = :cclasstrib";
            $params[':cclasstrib'] = [$dados['cclasstrib'], PDO::PARAM_STR];
        }

        // if (!empty($dados['ncm'])) {
        //     $sql .= " AND NCM = :ncm";
        //     $params[':ncm'] = [$dados['ncm'], PDO::PARAM_STR];
        // }

        if (!empty($dados['id_natureza_operacao'])) {
            $sql .= " AND ID_EST_NAT_OP = :id_est_nat_op";
            $params[':id_est_nat_op'] = [$dados['id_natureza_operacao'], PDO::PARAM_INT];
        }
    
        $banco->prepare($sql);
    
        foreach ($params as $param => [$value, $type]) {
            $banco->bindValue($param, $value, $type);
        }
    
        $banco->execute();
    
        if ($banco->rowCount() > 0) {
            return $banco->fetch(PDO::FETCH_ASSOC);
        }

        $error = $banco->errorInfo();
        throw new Exception("Não foi possível encontrar os dados do tributo IBS/CBS ". $error[2]);
    }
    

    /**
     * Função para obter os dados da classe tributária
     * @param int $id
     * @return array
     */
    function getEstCClassTrib($id) 
    {
        $banco = new c_banco_pdo();

        $banco->prepare("SELECT CCLASSTRIB, 
                                CST, 
                                TIPO_ALIQUOTA,
                                PRED_IBS,
                                PRED_CBS,
                                IND_G_TRIB_REGULAR,
                                IND_G_CRED_PRES_OPER,
                                IND_G_MONO_PADRAO,
                                IND_G_MONO_RETEN,
                                IND_G_MONO_RET,
                                TP_RBSN,
                                IND_DIR,
                                IND_DUIMP,
                                IND_DUIMP,
                                IND_GP_BIO_DIFERENCA,
                                IND_G_ESTORNO_CRED
                                FROM EST_CCLASS_TRIB 
                                WHERE ID = :id");

        $banco->bindValue(":id", $id, PDO::PARAM_INT);

        //$query = $banco->queryString();

        $banco->execute();

        if($banco->rowCount() > 0) {
            return $banco->fetch();
        } else {
            throw new Exception("Não foi possível encontrar os dados da classe tributária ou tipo do documento não suportado");
        }
    }

    /**
     * Função para obter os dados das regras da classe tributária
     * @param int $id
     * @return array
     */
    function getCstIbscbs($id) {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT IND_G_IBS_CBS,
                            IND_G_IBS_CBS_MONO,
                            IND_G_RED,
                            IND_G_DIF,
                            IND_G_TRANSF_CRED,
                            IND_G_CRED_PRES_IBS_ZFM,
                            IND_G_AJUSTE_COMPET,
                            IND_REDUTOR_BC FROM EST_CST_IBS_CBS WHERE CST = :cst");
        $banco->bindValue(":cst", $id, PDO::PARAM_STR);
        $banco->execute();

        if($banco->rowCount() > 0) {
            return $banco->fetch();
        } else {
            throw new Exception("Não foi possível encontrar os dados das regras da classe tributária");
        }
    }


    /**
     * Determina o CASE de tributação IBS/CBS baseado no código cClassTrib
     * 
     * CASE 1: Tributação Padrão (Sem Redução ou Diferimento)
     *         cClassTrib: 000001, 000002, 000003, 000004, 010001, 010002, 220001-220003, 221001, 222001, 830001
     * 
     * CASE 2: Tributação com Redução de Alíquota
     *         cClassTrib: 011001-011005, 200001-200052
     * 
     * CASE 3: Diferimento Total
     *         cClassTrib: 510001
     * 
     * CASE 4: Diferimento com Redução de Alíquota
     *         cClassTrib: 515001
     * 
     * CASE 5: Monofásica Padrão
     *         cClassTrib: 620001
     * 
     * CASE 6: Monofásica com Retenção
     *         cClassTrib: 620002, 620004, 620005
     * 
     * CASE 7: Monofásica Retida Anteriormente
     *         cClassTrib: 620003, 620006
     * 
     * CASE 8: Transferência de Crédito
     *         cClassTrib: 800001, 800002
     * 
     * CASE 9: Ajuste ZFM (Crédito Presumido)
     *         cClassTrib: 810001
     * 
     * CASE 10: Ajuste de Competência
     *          cClassTrib: 811001, 811002, 811003
     * 
     * CASE 11: Sem Tags Específicas (Isenção, Imunidade, Suspensão, Regime Específico)
     *          cClassTrib: 400001, 410001-410999, 550001-550021, 820001-820008
     * 
     * @param string $cClassTrib Código de Classificação Tributária (6 dígitos)
     * @return int Número do CASE de tributação (1-11)
     */
    public function determinaCaseTributacao($cClassTrib) 
    {
        // Remove espaços e converte para inteiro para comparação de ranges
        $codigo = intval(trim($cClassTrib));
        
        // CASE 1: Tributação Padrão (Sem Redução ou Diferimento)
        $case1_codes = [
            '000001', '000002', '000003', '000004',  // Tributação integral
            '010001', '010002',                       // Operações especiais sem redução
            '220001', '220002', '220003',             // Regime especial
            '221001',                                 // Redução de BC
            '222001',                                 // Redução de BC
            '830001'                                  // Exclusão BC Energia
        ];
        if (in_array($cClassTrib, $case1_codes)) {
            return 1;
        }
        
        // CASE 2: Tributação com Redução de Alíquota (gRed)
        // 011001-011005
        if ($codigo >= 11001 && $codigo <= 11005) {
            return 2;
        }
        // 200001-200052
        if ($codigo >= 200001 && $codigo <= 200052) {
            return 2;
        }
        
        // CASE 3: Diferimento Total (gDif)
        if ($cClassTrib == '510001') {
            return 3;
        }
        
        // CASE 4: Diferimento com Redução de Alíquota (gDif + gRed)
        if ($cClassTrib == '515001') {
            return 4;
        }
        
        // CASE 5: Monofásica Padrão (gMonoPadrao)
        if ($cClassTrib == '620001') {
            return 5;
        }
        
        // CASE 6: Monofásica com Retenção (gMonoReten)
        $case6_codes = ['620002', '620004', '620005'];
        if (in_array($cClassTrib, $case6_codes)) {
            return 6;
        }
        
        // CASE 7: Monofásica Retida Anteriormente (gMonoRet)
        $case7_codes = ['620003', '620006'];
        if (in_array($cClassTrib, $case7_codes)) {
            return 7;
        }
        
        // CASE 8: Transferência de Crédito (gTransfCred)
        $case8_codes = ['800001', '800002'];
        if (in_array($cClassTrib, $case8_codes)) {
            return 8;
        }
        
        // CASE 9: Ajuste ZFM - Crédito Presumido (gCredPresIBSZFM)
        if ($cClassTrib == '810001') {
            return 9;
        }
        
        // CASE 10: Ajuste de Competência (gAjusteCompet)
        $case10_codes = ['811001', '811002', '811003'];
        if (in_array($cClassTrib, $case10_codes)) {
            return 10;
        }
        
        // CASE 11: Sem Tags Específicas (Isenção, Imunidade, Suspensão, Regime Específico)
        // 400001 - Isenção
        if ($cClassTrib == '400001') {
            return 11;
        }
        // 410001-410999 - Imunidade/Não Incidência
        if ($codigo >= 410001 && $codigo <= 410999) {
            return 11;
        }
        // 550001-550021 - Suspensão
        if ($codigo >= 550001 && $codigo <= 550021) {
            return 11;
        }
        // 820001-820008 - Regime Específico (Declaração)
        if ($codigo >= 820001 && $codigo <= 820008) {
            return 11;
        }
        
        // Default: Tributação Padrão (caso não seja identificado)
        return 1;
    }
}