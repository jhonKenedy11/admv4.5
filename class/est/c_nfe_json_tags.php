<?php

/**
 * @package   adm
 * @name      c_nfe_json_tags
 * @version   4.5.0
 * @author    Joshua Silva
 *
 * Classe responsável por montar as tags da NFe (infNFe/ide, emit, enderEmit, etc.)
 * a partir de um array compatível com o JSON de entrada.
 *
 * Ela recebe uma instância de NFePHP\NFe\Make e os dados da nota
 * (decorrentes do JSON) e expõe métodos blocados: monta_ide, monta_emitente, etc.
 */

$dir = dirname(__FILE__);

include_once $dir . '/../../../sped/vendor/autoload.php';
include_once $dir . '/../../ped/c_pedido_venda_nf.php';
include_once $dir . '/c_nota_fiscal.php';

class c_nfe_json_tags
{
    /**
     * @var \NFePHP\NFe\Make
     */
    private $nfe;

    /**
     * @var array
     */
    private $dadosNota;

    /**
     * @var array
     */
    private $contexto;

    /**
     * @param \NFePHP\NFe\Make|null $nfe
     * @param array                 $dadosNota  Estrutura completa do JSON (decodificada)
     * @param array                 $contexto   Bloco contexto (empresa_id, filial_id, tp_amb, id_nf)
     */
    public function __construct(?\NFePHP\NFe\Make $nfe = null, array $dadosNota = [], array $contexto = [])
    {
        $this->nfe       = $nfe;
        $this->dadosNota = $dadosNota;
        $this->contexto  = $contexto;
    }

    /**
     * BLOCO IDE
     * Monta as tags infNFe + ide.
     *
     * @return string Chave da NFe gerada (Id da infNFe)
     */
    public function monta_ide()
    {
        $ide  = isset($this->dadosNota['ide']) ? $this->dadosNota['ide'] : [];
        $emit = isset($this->dadosNota['emitente']) ? $this->dadosNota['emitente'] : [];

        $nfe = $this->nfe;

        $cUF   = isset($ide['cUF']) ? $ide['cUF'] : substr($ide['cMunFG'] ?? '', 0, 2);
        $cNF   = isset($ide['cNF']) ? str_pad($ide['cNF'], 8, '0', STR_PAD_LEFT) : str_pad((string) mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        $mod   = isset($ide['mod']) ? $ide['mod'] : '55';
        $serie = isset($ide['serie']) ? $ide['serie'] : '1';
        $nNF   = isset($ide['nNF']) ? $ide['nNF'] : '0';
        $tpEmis = isset($ide['tpEmis']) ? $ide['tpEmis'] : '1';

        $cnpj  = isset($emit['CNPJ']) ? preg_replace('/\D/', '', $emit['CNPJ']) : '';
        $dhEmi = isset($ide['dhEmi']) ? $ide['dhEmi'] : date('Y-m-d\TH:i:sP');
        $timeEmi = strtotime($dhEmi);
        $ano   = date('y', $timeEmi);
        $mes   = date('m', $timeEmi);

        $chave = \NFePHP\Common\Keys::build(
            $cUF,
            $ano,
            $mes,
            $cnpj,
            $mod,
            $serie,
            $nNF,
            $tpEmis,
            $cNF
        );

        $cDV = substr($chave, -1);

        $stdInf = new \stdClass();
        $stdInf->versao = '4.00';
        $stdInf->Id     = $chave;
        $nfe->taginfNFe($stdInf);

        $std = new \stdClass();
        $std->cUF     = $cUF;
        $std->cNF     = $cNF;
        $std->natOp   = isset($ide['natOp']) ? $ide['natOp'] : '';
        $std->indPag  = isset($ide['indPag']) ? $ide['indPag'] : '0';
        $std->mod     = $mod;
        $std->serie   = $serie;
        $std->nNF     = $nNF;
        $std->dhEmi   = $dhEmi;
        $std->dhSaiEnt = isset($ide['dhSaiEnt']) ? $ide['dhSaiEnt'] : '';
        $std->tpNF    = isset($ide['tpNF']) ? $ide['tpNF'] : '1';
        $std->idDest  = isset($ide['idDest']) ? $ide['idDest'] : '1';
        $std->cMunFG  = isset($ide['cMunFG']) ? $ide['cMunFG'] : '';
        if (!empty($ide['cMunFGIBS'])) {
            $std->cMunFGIBS = $ide['cMunFGIBS'];
        }
        $std->tpImp   = isset($ide['tpImp']) ? $ide['tpImp'] : '1';
        $std->tpEmis  = $tpEmis;
        $std->cDV     = $cDV;
        $std->tpAmb   = isset($ide['tpAmb'])
            ? $ide['tpAmb']
            : (isset($this->contexto['tp_amb']) ? $this->contexto['tp_amb'] : '2');
        $std->finNFe  = isset($ide['finNFe']) ? $ide['finNFe'] : '1';
        $std->indFinal = isset($ide['indFinal']) ? $ide['indFinal'] : '1';
        $std->indPres  = isset($ide['indPres']) ? $ide['indPres'] : '1';
        $std->procEmi  = isset($ide['procEmi']) ? $ide['procEmi'] : '0';
        $std->verProc  = isset($ide['verProc']) ? $ide['verProc'] : '4.0.43';

        $nfe->tagide($std);

        if (!empty($ide['refNFe'])) {
            $stdRef = new \stdClass();
            $stdRef->refNFe = $ide['refNFe'];
            $nfe->tagrefNFe($stdRef);
        }

        return $chave;
    }

    /**
     * BLOCO EMITENTE (emit + enderEmit)
     */
    public function monta_emitente()
    {
        $emit = isset($this->dadosNota['emitente']) ? $this->dadosNota['emitente'] : [];
        $end  = isset($emit['endereco']) ? $emit['endereco'] : [];

        $nfe = $this->nfe;

        $std = new \stdClass();
        $std->xNome = isset($emit['xNome']) ? self::removeAcentos($emit['xNome']) : '';
        $std->xFant = isset($emit['xFant']) ? $emit['xFant'] : '';
        $std->IE    = isset($emit['IE']) ? $emit['IE'] : '';
        $std->IEST  = isset($emit['IEST']) ? $emit['IEST'] : '';
        $std->IM    = isset($emit['IM']) ? $emit['IM'] : '';
        $std->CNAE  = isset($emit['CNAE']) ? $emit['CNAE'] : '';
        $std->CRT   = isset($emit['CRT']) ? $emit['CRT'] : '1';

        $cnpj = isset($emit['CNPJ']) ? preg_replace('/\D/', '', $emit['CNPJ']) : '';
        $cpf  = isset($emit['CPF']) ? preg_replace('/\D/', '', $emit['CPF']) : '';

        $std->CNPJ = $cnpj;
        $std->CPF  = $cpf;

        $nfe->tagemit($std);

        $stdEnd = new \stdClass();
        $stdEnd->xLgr   = isset($end['xLgr']) ? self::removeAcentos($end['xLgr']) : '';
        $stdEnd->nro    = isset($end['nro']) ? $end['nro'] : '';
        if (!empty($end['xCpl'])) {
            $stdEnd->xCpl = self::removeAcentos($end['xCpl']);
        }
        $stdEnd->xBairro = isset($end['xBairro']) ? self::removeAcentos($end['xBairro']) : '';
        $stdEnd->cMun    = isset($end['cMun']) ? $end['cMun'] : '';
        $stdEnd->xMun    = isset($end['xMun']) ? self::removeAcentos($end['xMun']) : '';
        $stdEnd->UF      = isset($end['UF']) ? $end['UF'] : '';
        $cep             = isset($end['CEP']) ? preg_replace('/\D/', '', $end['CEP']) : '';
        if (strlen($cep) === 7) {
            $cep = '0' . $cep;
        }
        $stdEnd->CEP   = $cep;
        $stdEnd->cPais = isset($end['cPais']) ? $end['cPais'] : '1058';
        $stdEnd->xPais = isset($end['xPais']) ? $end['xPais'] : 'Brasil';
        $stdEnd->fone  = isset($end['fone']) ? $end['fone'] : '';

        $nfe->tagenderEmit($stdEnd);
    }

    /**
     * BLOCO DESTINATÁRIO (dest + enderDest)
     */
    public function monta_destinatario()
    {
        $dest = isset($this->dadosNota['destinatario']) ? $this->dadosNota['destinatario'] : [];
        $end  = isset($dest['endereco']) ? $dest['endereco'] : [];

        $nfe = $this->nfe;

        $std = new \stdClass();
        $std->xNome = isset($dest['xNome']) ? self::removeAcentos($dest['xNome']) : '';
        $std->indIEDest = isset($dest['indIEDest']) ? (int) $dest['indIEDest'] : 9;
        $std->IE    = isset($dest['IE']) ? $dest['IE'] : '';
        $std->CNPJ  = isset($dest['CNPJ']) ? preg_replace('/\D/', '', $dest['CNPJ']) : '';
        $std->CPF   = isset($dest['CPF']) ? preg_replace('/\D/', '', $dest['CPF']) : '';
        $std->IM    = isset($dest['IM']) ? $dest['IM'] : '';
        $std->ISUF  = isset($dest['ISUF']) ? $dest['ISUF'] : '';
        $std->email = isset($dest['email']) ? $dest['email'] : '';

        $nfe->tagdest($std);

        $stdEnd = new \stdClass();
        $stdEnd->xLgr   = isset($end['xLgr']) ? self::removeAcentos($end['xLgr']) : '';
        $stdEnd->nro    = isset($end['nro']) ? $end['nro'] : '';
        if (!empty($end['xCpl'])) {
            $stdEnd->xCpl = self::removeAcentos($end['xCpl']);
        }
        $stdEnd->xBairro = isset($end['xBairro']) ? self::removeAcentos($end['xBairro']) : '';
        $stdEnd->cMun    = isset($end['cMun']) ? $end['cMun'] : '';
        $stdEnd->xMun    = isset($end['xMun']) ? self::removeAcentos($end['xMun']) : '';
        $stdEnd->UF      = isset($end['UF']) ? $end['UF'] : '';
        $cep             = isset($end['CEP']) ? preg_replace('/\D/', '', $end['CEP']) : '';
        if (strlen($cep) === 7) {
            $cep = '0' . $cep;
        }
        $stdEnd->CEP   = $cep;
        $stdEnd->cPais = isset($end['cPais']) ? $end['cPais'] : '1058';
        $stdEnd->xPais = isset($end['xPais']) ? $end['xPais'] : 'Brasil';
        $stdEnd->fone  = isset($end['fone']) ? $end['fone'] : '';

        $nfe->tagenderDest($stdEnd);
    }

    /**
     * BLOCO PRODUTOS (det/prod + imposto básico)
     */
    public function monta_produtos()
    {
        $itens = isset($this->dadosNota['itens']) ? $this->dadosNota['itens'] : [];
        if (empty($itens)) {
            return;
        }

        $nfe = $this->nfe;
        $n   = 1;

        foreach ($itens as $item) {
            $std = new \stdClass();
            $std->item = $n;
            $std->cProd = $item['cProd'] ?? '';
            $std->cEAN  = $item['cEAN']  ?? '0000000000000';
            $std->xProd = isset($item['xProd']) ? self::removeAcentos($item['xProd']) : '';
            $std->NCM   = $item['NCM']   ?? '';
            $std->EXTIPI = $item['EXTIPI'] ?? '';
            $std->CFOP  = $item['CFOP']  ?? '';
            $std->uCom  = $item['uCom']  ?? '';
            $std->qCom  = $item['qCom']  ?? '0.0000';
            $std->vUnCom = $item['vUnCom'] ?? '0.00';
            $std->vProd  = $item['vProd']  ?? '0.00';
            $std->cEANTrib = $item['cEANTrib'] ?? '0000000000000';
            $std->uTrib    = $item['uTrib']    ?? ($std->uCom ?: '');
            $std->qTrib    = $item['qTrib']    ?? ($std->qCom ?: '0.0000');
            $std->vUnTrib  = $item['vUnTrib']  ?? ($std->vUnCom ?: '0.00');
            $std->vFrete   = $item['vFrete']   ?? '0.00';
            $std->vSeg     = $item['vSeg']     ?? '0.00';
            $std->vDesc    = $item['vDesc']    ?? '0.00';
            $std->vOutro   = $item['vOutro']   ?? '0.00';
            $std->indTot   = $item['indTot']   ?? '1';
            $std->xPed     = $item['xPed']     ?? '';
            if (!empty($item['nItemPed'])) {
                $std->nItemPed = $item['nItemPed'];
            }
            if (!empty($item['nFCI'])) {
                $std->nFCI = $item['nFCI'];
            }
            if (!empty($item['cBenef'])) {
                $std->cBenef = $item['cBenef'];
            }
            if (!empty($item['CEST'])) {
                $std->CEST = $item['CEST'];
                $std->indEscala = $item['indEscala'] ?? 'S';
            }

            $nfe->tagprod($std);

            // impostos básicos (ICMS, PIS, COFINS, IPI) vindos do JSON
            $this->monta_impostos_item($item, $n);

            $n++;
        }
    }

    /**
     * Converte o bloco imposto do item em tags ICMS/PIS/COFINS/IPI.
     *
     * @param array $item
     * @param int   $nItem
     * @return void
     */
    private function monta_impostos_item(array $item, $nItem)
    {
        if (empty($item['imposto']) || !is_array($item['imposto'])) {
            return;
        }
        $imp = $item['imposto'];

        // ICMS
        if (!empty($imp['icms']) && is_array($imp['icms'])) {
            $icms   = $imp['icms'];
            $codigo = (string)($icms['CSOSN'] ?? ($icms['CST'] ?? ''));

            // CSOSN (Simples Nacional) – segue o mesmo padrão do p_nfephp_40 (tagICMSSN)
            $csosnSn = ['101','102','103','201','202','203','300','400','500','900'];

            if ($codigo !== '' && in_array($codigo, $csosnSn, true)) {
                $std = new \stdClass();
                $std->item  = $nItem;
                $std->orig  = $icms['orig'];
                $std->CSOSN = $icms['CSOSN'] ?? $codigo;

                // Campos opcionais comuns usados no p_nfephp_40 para SN
                if (isset($icms['modBC'])) {
                    $std->modBC = $icms['modBC'];
                }
                if (isset($icms['vBC'])) {
                    $std->vBC = $icms['vBC'];
                }
                if (isset($icms['pICMS'])) {
                    $std->pICMS = $icms['pICMS'];
                }
                if (isset($icms['vICMS'])) {
                    $std->vICMS = $icms['vICMS'];
                }
                if (isset($icms['pRedBC'])) {
                    $std->pRedBC = $icms['pRedBC'];
                }

                // ST (retida) quando existir
                if (isset($icms['vBCSTRet'])) {
                    $std->vBCSTRet = $icms['vBCSTRet'];
                }
                if (isset($icms['pST'])) {
                    $std->pST = $icms['pST'];
                }
                if (isset($icms['vICMSSTRet'])) {
                    $std->vICMSSTRet = $icms['vICMSSTRet'];
                }

                $this->nfe->tagICMSSN($std);
            } else {
                // Regime normal – segue o padrão do p_nfephp_40 (tagICMS)
                $std = new \stdClass();
                $std->item = $nItem;
                foreach ($icms as $k => $v) {
                    $std->{$k} = $v;
                }
                $this->nfe->tagICMS($std);
            }
        }

        // PIS
        if (!empty($imp['pis']) && is_array($imp['pis'])) {
            $std = new \stdClass();
            $std->item = $nItem;
            foreach ($imp['pis'] as $k => $v) {
                $std->{$k} = $v;
            }
            $this->nfe->tagPIS($std);
        }

        // COFINS
        if (!empty($imp['cofins']) && is_array($imp['cofins'])) {
            $std = new \stdClass();
            $std->item = $nItem;
            foreach ($imp['cofins'] as $k => $v) {
                $std->{$k} = $v;
            }
            $this->nfe->tagCOFINS($std);
        }

        // IPI
        if (!empty($imp['ipi']) && is_array($imp['ipi'])) {
            $std = new \stdClass();
            $std->item = $nItem;
            foreach ($imp['ipi'] as $k => $v) {
                $std->{$k} = $v;
            }
            $this->nfe->tagIPI($std);
        }
    }

    /**
     * BLOCO TOTAIS (ICMSTot)
     */
    public function monta_totais()
    {
        $tot = isset($this->dadosNota['totais']) ? $this->dadosNota['totais'] : [];
        if (empty($tot)) {
            return;
        }

        $std = new \stdClass();
        $std->vBC        = $tot['vBC']        ?? 0;
        $std->vICMS      = $tot['vICMS']      ?? 0;
        $std->vICMSDeson = $tot['vICMSDeson'] ?? 0;
        $std->vFCP       = $tot['vFCP']       ?? 0;
        $std->vBCST      = $tot['vBCST']      ?? 0;
        $std->vST        = $tot['vST']        ?? 0;
        $std->vFCPST     = $tot['vFCPST']     ?? 0;
        $std->vFCPSTRet  = $tot['vFCPSTRet']  ?? 0;
        $std->vProd      = $tot['vProd']      ?? 0;
        $std->vFrete     = $tot['vFrete']     ?? 0;
        $std->vSeg       = $tot['vSeg']       ?? 0;
        $std->vDesc      = $tot['vDesc']      ?? 0;
        $std->vII        = $tot['vII']        ?? 0;
        $std->vIPI       = $tot['vIPI']       ?? 0;
        $std->vIPIDevol  = $tot['vIPIDevol']  ?? 0;
        $std->vPIS       = $tot['vPIS']       ?? 0;
        $std->vCOFINS    = $tot['vCOFINS']    ?? 0;
        $std->vOutro     = $tot['vOutro']     ?? 0;
        $std->vNF        = $tot['vNF']        ?? 0;
        $std->vTotTrib   = $tot['vTotTrib']   ?? 0;

        $this->nfe->tagICMSTot($std);
    }

    /**
     * BLOCO TRANSPORTE (transp + transporta básico)
     */
    public function monta_transporte()
    {
        $transp = isset($this->dadosNota['transporte']) ? $this->dadosNota['transporte'] : [];
        $nfe    = $this->nfe;

        $std = new \stdClass();
        $std->modFrete = $transp['modFrete'] ?? '9';
        $nfe->tagtransp($std);

        if (!empty($transp['transportador']) && is_array($transp['transportador']) && $std->modFrete !== '9') {
            $t = $transp['transportador'];
            $stdT = new \stdClass();
            $stdT->CNPJ = isset($t['CNPJ']) ? preg_replace('/\D/', '', $t['CNPJ']) : '';
            $stdT->CPF  = isset($t['CPF']) ? preg_replace('/\D/', '', $t['CPF']) : '';
            $stdT->xNome = isset($t['xNome']) ? self::removeAcentos($t['xNome']) : '';
            $stdT->IE    = $t['IE']    ?? '';
            $stdT->xEnder = isset($t['xEnder']) ? self::removeAcentos($t['xEnder']) : '';
            $stdT->xMun   = isset($t['xMun'])   ? self::removeAcentos($t['xMun'])   : '';
            $stdT->UF     = $t['UF']    ?? '';
            $nfe->tagtransporta($stdT);
        }
    }

    /**
     * Validações básicas de dados do cliente / operação seguindo regras da SEFAZ.
     *
     * @throws \Exception
     */
    private function valida_dados_basicos_sefaz()
    {
        $ide  = $this->dadosNota['ide']          ?? [];
        $emit = $this->dadosNota['emitente']     ?? [];
        $dest = $this->dadosNota['destinatario'] ?? [];

        $endEmit = $emit['endereco'] ?? [];
        $endDest = $dest['endereco'] ?? [];

        // CNPJ/CPF obrigatório para destinatário
        $cnpjDest = preg_replace('/\D/', '', (string) ($dest['CNPJ'] ?? ''));
        $cpfDest  = preg_replace('/\D/', '', (string) ($dest['CPF'] ?? ''));
        if ($cnpjDest === '' && $cpfDest === '') {
            throw new \Exception('TipoErro=1; Destinatário sem CNPJ/CPF informado. Conferir cadastro do cliente.');
        }

        // Código do município e UF obrigatórios para destinatário
        if (empty($endDest['cMun']) || empty($endDest['UF'])) {
            throw new \Exception('TipoErro=1; Destinatário sem código de município/UF. Conferir dados do endereço no cadastro do cliente.');
        }

        // IE obrigatória quando indIEDest = 1 (contribuinte ICMS)
        $indIEDest = (int) ($dest['indIEDest'] ?? 9);
        $ieDest    = (string) ($dest['IE'] ?? '');
        $cpfBruto  = (string) ($dest['CPF'] ?? '');

        if ($indIEDest === 1 && $ieDest === '') {
            throw new \Exception('TipoErro=1; Inscrição estadual do destinatário obrigatória para contribuinte ICMS.');
        }
        if ($ieDest !== '' && !ctype_digit($ieDest) && $cpfBruto === '') {
            throw new \Exception('TipoErro=1; Inscrição estadual do destinatário inválida. Verifique se o campo contém apenas números. ('.$ieDest.')');
        }

        // Consistência entre UF de emitente/destinatário e idDest
        $ufEmit = (string) ($endEmit['UF'] ?? '');
        $ufDest = (string) ($endDest['UF'] ?? '');
        $idDest = (string) ($ide['idDest'] ?? '');

        if ($ufEmit === '' || $ufDest === '' || $idDest === '') {
            return;
        }

        // 1 = operação interna, 2 = interestadual
        if ($ufEmit === $ufDest && $idDest === '2') {
            throw new \Exception('TipoErro=1; idDest indica operação interestadual, mas UF do destinatário é igual à do emitente. Verifique a natureza de operação.');
        }
        if ($ufEmit !== $ufDest && $idDest === '1') {
            throw new \Exception('TipoErro=1; idDest indica operação interna, mas UF do destinatário é diferente da UF do emitente. Verifique a natureza de operação.');
        }
    }

    /**
     * BLOCO COBRANÇA + PAGAMENTOS (dup, pag, detPag)
     */
    public function monta_cobranca_pagamentos()
    {
        $this->valida_dados_basicos_sefaz();

        $cobranca   = isset($this->dadosNota['cobranca'])   ? $this->dadosNota['cobranca']   : [];
        $pagamentos = isset($this->dadosNota['pagamentos']) ? $this->dadosNota['pagamentos'] : [];

        // duplicatas
        if (!empty($cobranca['dup']) && is_array($cobranca['dup'])) {
            foreach ($cobranca['dup'] as $dup) {
                $std = new \stdClass();
                $std->nDup = $dup['nDup'] ?? '';
                $std->dVenc = $dup['dVenc'] ?? '';
                $std->vDup  = $dup['vDup']  ?? 0;
                $this->nfe->tagdup($std);
            }
        }

        // tag pag (vTroco) e detPag
        $stdPag = new \stdClass();
        $stdPag->vTroco = null;
        $this->nfe->tagpag($stdPag);

        if (!empty($pagamentos)) {
            foreach ($pagamentos as $pag) {
                $std = new \stdClass();
                $std->tPag  = $pag['tPag']  ?? '15';
                $std->vPag  = $pag['vPag']  ?? 0;
                $std->indPag= $pag['indPag']?? '0';
                if (!empty($pag['xPag'])) {
                    $std->xPag = $pag['xPag'];
                }
                $this->nfe->tagdetPag($std);
            }
        }
    }

    /**
     * BLOCO INFADIC (infAdic)
     */
    public function monta_infAdic()
    {
        $inf = isset($this->dadosNota['infAdic']) ? $this->dadosNota['infAdic'] : [];
        if (empty($inf)) {
            return;
        }

        $std = new \stdClass();
        $std->infCpl    = isset($inf['infCpl']) ? self::removeAcentos($inf['infCpl']) : '';
        $std->infAdFisco= $inf['infAdFisco'] ?? '';
        $this->nfe->taginfAdic($std);
    }

    /**
     * Utilitário para remover acentos (mesma ideia do p_nfephp_40).
     *
     * @param string $string
     * @return string
     */
    private static function removeAcentos($string)
    {
        if (!is_string($string) || $string === '') {
            return $string;
        }

        $conversao = array(
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
            'é' => 'e', 'ê' => 'e',
            'í' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n',
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A',
            'É' => 'E', 'Ê' => 'E',
            'Í' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C', 'Ñ' => 'N'
        );

        return strtr($string, $conversao);
    }

    /**
     * Monta um JSON de "espelho" da NFe.
     *
     * Quando $origem = 'PED', espera:
     *  - $pedido: array retornado por select_pedidoVenda() (uma linha) ou a própria linha
     *  - $itens:  array retornado por select_pedido_item_id('1')
     *
     * @param string|null $origem
     * @param array       $pedido
     * @param array       $itens
     * @param array       $contexto
     * @return string
     */
    public function monta_json_espelho($origem = null, array $pedido = [], array $itens = [], array $contexto = [])
    {
        // Espelho a partir de pedido de venda
        if ($origem === 'PED') {
            // Normaliza a estrutura: pode vir como [0 => row] ou só row
            $pedidoRow = (isset($pedido[0]) && is_array($pedido[0])) ? $pedido[0] : $pedido;

            $numeroPedido = $pedidoRow['PEDIDO'] ?? ($pedidoRow['ID'] ?? null);

            // Bloco CONTEXTO
            $dados['contexto'] = [
                'empresa_id' => $contexto['empresa_id'] ?? null,
                'filial_id'  => $contexto['filial_id']  ?? ($pedidoRow['CCUSTO'] ?? null),
                'tp_amb'     => $contexto['tp_amb']     ?? 2,
                'id_nf'      => $contexto['id_nf']      ?? null,
            ];

            // Bloco EMITENTE (busca dados da empresa a partir do centro de custo),
            // seguindo o mesmo padrão de p_nfephp_40 / c_nota_fiscal.
            $emitente = [];
            $filialId = $dados['contexto']['filial_id'];
            if (!empty($filialId)) {
                $nfObj   = new c_nota_fiscal();
                $empresa = $nfObj->select_empresa_centro_custo($filialId);
                if (is_array($empresa) && !empty($empresa[0])) {
                    $rowEmp = $empresa[0];
                    $cnpjCpfEmit = preg_replace('/\D/', '', (string)($rowEmp['CNPJCPF'] ?? ''));

                    $emitente = [
                        'xNome' => $rowEmp['NOME']         ?? '',
                        'xFant' => $rowEmp['NOMEFANTASIA'] ?? ($rowEmp['NOME'] ?? ''),
                        'IE'    => $rowEmp['INSCESTADUAL'] ?? '',
                        'IEST'  => '',
                        'IM'    => $rowEmp['INSMUNICIPAL'] ?? '',
                        'CNAE'  => $rowEmp['CNAE']         ?? '',
                        'CRT'   => (string)($rowEmp['CRT'] ?? '3'),
                        'endereco' => [
                            'xLgr'   => $rowEmp['ENDERECO']    ?? '',
                            'nro'    => $rowEmp['NUMERO']      ?? '',
                            'xCpl'   => $rowEmp['COMPLEMENTO'] ?? '',
                            'xBairro'=> $rowEmp['BAIRRO']      ?? '',
                            'cMun'   => $rowEmp['CODMUNICIPIO']?? '',
                            'xMun'   => $rowEmp['CIDADE']      ?? '',
                            'UF'     => $rowEmp['UF']          ?? '',
                            'CEP'    => $rowEmp['CEP']         ?? '',
                            'cPais'  => '1058',
                            'xPais'  => 'Brasil',
                            'fone'   => $rowEmp['FONE']        ?? '',
                        ],
                    ];

                    // Empresa (jurídica) => CNPJ
                    $emitente['CNPJ'] = $cnpjCpfEmit;
                    $emitente['CPF']  = '';
                }
            }

            if (!empty($emitente)) {
                $dados['emitente'] = $emitente;
            }

            // Bloco IDE (dados básicos da operação)
            // A natureza de operação deve seguir a mesma lógica da nota fiscal:
            // usar a descrição da EST_NAT_OP (c_nota_fiscal::getNatOperacao).
            // Prioriza o idNatop vindo do contexto (formulário), senão cai no que veio do pedido.
            $natOpId = $contexto['idNatop'] ?? ($pedidoRow['IDNATOP'] ?? ($pedidoRow['idNatop'] ?? null));
            $natOp   = $pedidoRow['NATOPERACAO'] ?? ($pedidoRow['natOperacao'] ?? null);

            if (empty($natOp) && !empty($natOpId)) {
                $nfTmp = new c_nota_fiscal();
                $nfTmp->setIdNatop($natOpId);
                $natOp = $nfTmp->getNatOperacao();
            }

            if (empty($natOp)) {
                $natOp = 'VENDA'; // fallback seguro caso não consiga localizar
            }

            $serie = $pedidoRow['SERIE'] ?? '1';

            // Define UF do emitente e do destinatário para calcular idDest corretamente
            $ufEmit = $emitente['endereco']['UF'] ?? '';
            $ufDest = $pedidoRow['UF'] ?? ($pedidoRow['uf'] ?? '');
            $idDest = '1';
            if (!empty($ufEmit) && !empty($ufDest)) {
                $idDest = ($ufEmit === $ufDest) ? '1' : '2'; // 1=interno, 2=interestadual
            }

            $dados['ide'] = [
                'cUF'      => $emitente['endereco']['UF'] ?? '41',
                'cNF'      => $numeroPedido,
                'natOp'    => $natOp,
                'indPag'   => '0',
                'mod'      => '55',
                'serie'    => $serie,
                'nNF'      => $numeroPedido,
                'dhEmi'    => date('Y-m-d\TH:i:sP'),
                'dhSaiEnt' => date('Y-m-d\TH:i:sP'),
                'tpNF'     => '1',
                'idDest'   => $idDest,
                // Município de ocorrência da operação: usa o município do emitente (filial),
                // como no fluxo de p_nfephp_40 / c_nota_fiscal.
                'cMunFG'   => $emitente['endereco']['cMun'] ?? ($pedidoRow['CODMUNICIPIO'] ?? ($pedidoRow['codmunicipio'] ?? '')),
                'cMunFGIBS'=> '',
                'tpImp'    => '1',
                'tpEmis'   => '1',
                'tpAmb'    => $dados['contexto']['tp_amb'],
                'finNFe'   => '1',
                'indFinal' => '1',
                'indPres'  => '1',
                'procEmi'  => '0',
                'verProc'  => '4.0.43',
                'refNFe'   => '',
            ];

            // Bloco DESTINATÁRIO
            $cnpjCpfRaw = $pedidoRow['CNPJCPF'] ?? ($pedidoRow['cnpjcpf'] ?? '');
            $cnpjCpf    = preg_replace('/\D/', '', (string) $cnpjCpfRaw);
            $pessoaTipo = $pedidoRow['PESSOA'] ?? ($pedidoRow['pessoa'] ?? null);

            $dest = [
                'xNome'     => $pedidoRow['NOME'] ?? ($pedidoRow['NOMEREDUZIDO'] ?? ($pedidoRow['nome'] ?? '')),
                'indIEDest' => 9,
                'IE'        => $pedidoRow['INSCESTRG'] ?? ($pedidoRow['inscestrg'] ?? ''),
                'ISUF'      => '',
                'IM'        => '',
                'email'     => $pedidoRow['EMAIL'] ?? ($pedidoRow['email'] ?? ''),
                'endereco'  => [
                    'xLgr'   => $pedidoRow['ENDERECO'] ?? ($pedidoRow['endereco'] ?? ''),
                    'nro'    => $pedidoRow['NUMERO'] ?? ($pedidoRow['numero'] ?? ''),
                    'xCpl'   => $pedidoRow['COMPLEMENTO'] ?? ($pedidoRow['complemento'] ?? ''),
                    'xBairro'=> $pedidoRow['BAIRRO'] ?? ($pedidoRow['bairro'] ?? ''),
                    'cMun'   => $pedidoRow['CODMUNICIPIO'] ?? ($pedidoRow['codmunicipio'] ?? ''),
                    'xMun'   => $pedidoRow['CIDADE'] ?? ($pedidoRow['cidade'] ?? ''),
                    'UF'     => $pedidoRow['UF'] ?? ($pedidoRow['uf'] ?? ''),
                    'CEP'    => $pedidoRow['CEP'] ?? ($pedidoRow['cep'] ?? ''),
                    'cPais'  => '1058',
                    'xPais'  => 'Brasil',
                    'fone'   => ($pedidoRow['FONEAREA'] ?? ($pedidoRow['fonearea'] ?? '')) .
                                ($pedidoRow['FONE'] ?? ($pedidoRow['fone'] ?? '')),
                ],
            ];

            if ($pessoaTipo === 'J') {
                $dest['CNPJ'] = $cnpjCpf;
                $dest['CPF']  = '';
            } elseif ($pessoaTipo === 'F') {
                $dest['CNPJ'] = '';
                $dest['CPF']  = $cnpjCpf;
            } else {
                $dest['CNPJ'] = '';
                $dest['CPF']  = '';
            }

            $dados['destinatario'] = $dest;

            // Bloco ITENS + cálculo de impostos via c_pedidoVendaNf (apenas_calculo)
            $calcImpostos = new c_pedidoVendaNf();

            $dados['itens'] = [];
            $totalProdutos  = 0.0;
            $totalDesconto  = 0.0;
            $totalIcmsBC    = 0.0;
            $totalIcms      = 0.0;
            $totalPisBC     = 0.0;
            $totalPis       = 0.0;
            $totalCofinsBC  = 0.0;
            $totalCofins    = 0.0;
            $totalIpi       = 0.0;

            $ufDest     = $dest['endereco']['UF'] ?? '';
            $tipoPessoa = $pessoaTipo;
            $centroCusto = $dados['contexto']['filial_id'];
            $natOpId    = $natOpId ?? ($pedidoRow['IDNATOP'] ?? ($pedidoRow['idNatop'] ?? '1'));

            foreach ($itens as $idx => $item) {
                $qtd   = (float) ($item['QTSOLICITADA'] ?? ($item['qtsolicitada'] ?? 0));
                $vUn   = (float) ($item['UNITARIO'] ?? ($item['unitario'] ?? 0));
                $vProd = (float) ($item['TOTAL'] ?? ($item['total'] ?? ($qtd * $vUn)));
                $vDesc = (float) ($item['DESCONTO'] ?? ($item['desconto'] ?? 0));

                $totalProdutos += $vProd;
                $totalDesconto += $vDesc;

                // Modo "array" do calculaImpostosNfe (apenas_calculo = true)
                $dadosItemImposto = [
                    'despAcessorias' => (float) ($item['DESPACESSORIAS'] ?? ($item['despacessorias'] ?? 0)),
                    'tribIcms'       => $item['TRIBICMS'] ?? ($item['tribicms'] ?? ''),
                    'item_estoque'   => $item['ITEMESTOQUE'] ?? ($item['itemestoque'] ?? 0),
                    'desconto'       => $vDesc,
                    'produto_valor'  => $vProd,
                    'total'          => $vProd,
                    'frete'          => (float) ($item['FRETE'] ?? ($item['frete'] ?? 0)),
                    'origem'         => $item['ORIGEM'] ?? ($item['origem'] ?? ''),
                    'ncm'            => $item['NCM'] ?? ($item['ncm'] ?? ''),
                    'cest'           => $item['CEST'] ?? ($item['cest'] ?? ''),
                    'quantidade'     => $qtd,
                ];

                $resultadoImpostos = null;
                if (!empty($natOpId) && !empty($ufDest) && !empty($tipoPessoa) && !empty($centroCusto)) {
                    $difalContext = [
                        'ie' => trim($pedidoRow['INSCESTRG'] ?? ($pedidoRow['inscestrg'] ?? '')),
                        'vendaPresencial' => $pedidoRow['VENDAPRESENCIAL'] ?? ($pedidoRow['vendaPresencial'] ?? 'N'),
                    ];

                    $resultadoImpostos = $calcImpostos->calculaImpostosNfe(
                        $dadosItemImposto,
                        $natOpId,
                        $ufDest,
                        $tipoPessoa,
                        $centroCusto,
                        true,
                        $difalContext
                    );

                    // Se houve erro no cálculo dos tributos, interrompe a geração do espelho
                    if (is_array($resultadoImpostos)
                        && array_key_exists('success', $resultadoImpostos)
                        && $resultadoImpostos['success'] === false
                    ) {
                        $msgErroBase = !empty($resultadoImpostos['error'])
                            ? $resultadoImpostos['error']
                            : 'Tributos não encontrados';

                        // Detalha qual combinação era esperada em EST_NAT_OP_TRIBUTO
                        $esperado = sprintf(
                            ' [NatOp %s | UF %s | Pessoa %s | Origem %s | Trib.ICMS %s | NCM %s | CEST %s | Produto %s]',
                            (string) $natOpId,
                            (string) $ufDest,
                            (string) $tipoPessoa,
                            (string) ($dadosItemImposto['origem'] ?? ''),
                            (string) ($dadosItemImposto['tribIcms'] ?? ''),
                            (string) ($dadosItemImposto['ncm'] ?? ''),
                            (string) ($dadosItemImposto['cest'] ?? ''),
                            (string) ($dadosItemImposto['item_estoque'] ?? '')
                        );

                        $msgErro = $msgErroBase . $esperado;

                        throw new \Exception(
                            'TipoErro=2; Falha no cálculo de tributos para o item ' . ($idx + 1) . ' NatOp: ' . $natOpId . ' : ' . $msgErro
                        );
                    }
                }

                $valoresTrib = ($resultadoImpostos && !empty($resultadoImpostos['success']) && !empty($resultadoImpostos['valores']))
                    ? $resultadoImpostos['valores']
                    : null;

                // Atualiza totais de impostos
                if ($valoresTrib) {
                    $totalIcmsBC   += (float) ($valoresTrib['bcIcms']   ?? 0);
                    $totalIcms     += (float) ($valoresTrib['vlIcms']   ?? 0);
                    $totalPisBC    += (float) ($valoresTrib['bcPis']    ?? 0);
                    $totalPis      += (float) ($valoresTrib['vlPis']    ?? 0);
                    $totalCofinsBC += (float) ($valoresTrib['bcCofins'] ?? 0);
                    $totalCofins   += (float) ($valoresTrib['vlCofins'] ?? 0);
                    $totalIpi      += (float) ($valoresTrib['vlIpi']    ?? 0);
                }

                $itemJson = [
                    'nItem'    => $idx + 1,
                    'cProd'    => $item['ITEMESTOQUE'] ?? ($item['itemestoque'] ?? ''),
                    'cEAN'     => $item['CODIGOBARRAS'] ?? ($item['codigobarras'] ?? '0000000000000'),
                    'xProd'    => $item['DESCRICAO'] ?? ($item['descricao'] ?? ''),
                    'NCM'      => $item['NCM'] ?? ($item['ncm'] ?? ''),
                    'EXTIPI'   => '',
                    'CFOP'     => $valoresTrib['cfop'] ?? '',
                    'cBenef'   => '',
                    'uCom'     => $item['UNIDADE'] ?? ($item['unidade'] ?? ''),
                    'qCom'     => number_format($qtd, 4, '.', ''),
                    'vUnCom'   => number_format($vUn, 2, '.', ''),
                    'vProd'    => number_format($vProd, 2, '.', ''),
                    'cEANTrib' => $item['CODIGOBARRAS'] ?? ($item['codigobarras'] ?? '0000000000000'),
                    'uTrib'    => $item['UNIDADE'] ?? ($item['unidade'] ?? ''),
                    'qTrib'    => number_format($qtd, 4, '.', ''),
                    'vUnTrib'  => number_format($vUn, 2, '.', ''),
                    'vFrete'   => (float) ($item['FRETE'] ?? ($item['frete'] ?? 0)),
                    'vSeg'     => 0.00,
                    'vDesc'    => number_format($vDesc, 2, '.', ''),
                    'vOutro'   => (float) ($item['DESPACESSORIAS'] ?? ($item['despacessorias'] ?? 0)),
                    'indTot'   => '1',
                    'xPed'     => $numeroPedido,
                    'nItemPed' => $item['NRITEM'] ?? ($item['nritem'] ?? null),
                ];

                // Bloco IMPOSTO no padrão NFeJSON
                if ($valoresTrib) {
                    $itemJson['imposto'] = [
                        'icms' => [
                            'orig'   => $valoresTrib['origem']          ?? ($item['ORIGEM'] ?? ($item['origem'] ?? '0')),
                            'CST'    => $valoresTrib['icmsSaida']       ?? ($dadosItemImposto['tribIcms'] ?? ''),
                            'modBC'  => $calcImpostos->modalidade_calculo ?? null,
                            'vBC'    => $valoresTrib['bcIcms']          ?? 0,
                            'pICMS'  => $valoresTrib['icms_aliq']       ?? 0,
                            'vICMS'  => $valoresTrib['vlIcms']          ?? 0,
                            'pRedBC' => $calcImpostos->reducao_base_calculo_perc ?? 0,
                        ],
                        'pis' => [
                            'CST'   => $valoresTrib['pis_cst']   ?? null,
                            'vBC'   => $valoresTrib['bcPis']    ?? 0,
                            'pPIS'  => $valoresTrib['pis_aliq'] ?? 0,
                            'vPIS'  => $valoresTrib['vlPis']    ?? 0,
                        ],
                        'cofins' => [
                            'CST'      => $valoresTrib['cofins_cst']   ?? null,
                            'vBC'      => $valoresTrib['bcCofins']    ?? 0,
                            'pCOFINS'  => $valoresTrib['cofins_aliq'] ?? 0,
                            'vCOFINS'  => $valoresTrib['vlCofins']    ?? 0,
                        ],
                        'ipi' => [
                            'cEnq' => '999',
                            'CST'  => $valoresTrib['ipi_cst']   ?? null,
                            'vBC'  => $vProd,
                            'pIPI' => $valoresTrib['ipi_aliq'] ?? 0,
                            'vIPI' => $valoresTrib['vlIpi']    ?? 0,
                        ],
                    ];
                }

                $dados['itens'][] = $itemJson;
            }

            // Bloco TOTAIS (básico, sem impostos)
            $valorFrete  = (float) ($pedidoRow['FRETE'] ?? ($pedidoRow['frete'] ?? 0));
            $despAcess   = (float) ($pedidoRow['DESPACESSORIAS'] ?? ($pedidoRow['despacessorias'] ?? 0));
            $valorTotal  = (float) ($pedidoRow['TOTAL'] ?? ($pedidoRow['total'] ?? ($totalProdutos - $totalDesconto + $valorFrete + $despAcess)));

            $dados['totais'] = [
                'vProd'      => $totalProdutos,
                'vDesc'      => $totalDesconto,
                'vFrete'     => $valorFrete,
                'vOutro'     => $despAcess,
                'vNF'        => $valorTotal,
                'vBC'        => $totalIcmsBC,
                'vICMS'      => $totalIcms,
                'vICMSDeson' => 0.00,
                'vFCP'       => 0.00,
                'vBCST'      => 0.00,
                'vST'        => 0.00,
                'vFCPST'     => 0.00,
                'vFCPSTRet'  => 0.00,
                'vII'        => 0.00,
                'vIPI'       => $totalIpi,
                'vIPIDevol'  => 0.00,
                'vPIS'       => $totalPis,
                'vCOFINS'    => $totalCofins,
                'vTotTrib'   => $totalIcms + $totalPis + $totalCofins + $totalIpi,
            ];

            // Bloco TRANSPORTE (simplificado)
            $modFrete = (string) ($pedidoRow['MODFRETE'] ?? ($pedidoRow['modfrete'] ?? '9'));
            $dados['transporte'] = [
                'modFrete'      => $modFrete,
                'transportador' => [
                    'CNPJ'  => '',
                    'CPF'   => '',
                    'xNome' => '',
                    'IE'    => '',
                    'xEnder'=> '',
                    'xMun'  => '',
                    'UF'    => '',
                ],
            ];

            // Bloco COBRANÇA e PAGAMENTOS (básico, uma parcela)
            $dados['cobranca'] = [
                'dup' => [],
            ];

            $descPgto = $pedidoRow['DESCPGTO'] ?? ($pedidoRow['descpgto'] ?? '');
            $dados['pagamentos'] = [
                [
                    'indPag' => '1',
                    'tPag'   => '15',
                    'xPag'   => $descPgto,
                    'vPag'   => $valorTotal,
                ],
            ];

            // Bloco INFADIC
            $obsPedido  = $pedidoRow['OBS'] ?? ($pedidoRow['obs'] ?? '');
            $obsCliente = $pedidoRow['OBSCLIENTE'] ?? ($pedidoRow['obscliente'] ?? '');
            $infCpl     = trim("Pedido: {$numeroPedido} {$obsPedido} {$obsCliente}");

            $dados['infAdic'] = [
                'infCpl'     => $infCpl,
                'infAdFisco' => '',
            ];

            return json_encode($dados);
        }

        // Para outras origens, apenas retorna o JSON do array recebido
        return json_encode($pedido);
    }
}

