<?php



//Municipios que utilizam padrao IPM
function getMunicipiosIpm(): array
{  
    return [
        ['Pinhais', 'PR', '4119152'],
        ['Penha', 'SC', '4212502']
    ];
}

//Configuracoes IPM
function getConfigIPM(): array
{
    return [
        // A chave 'municipios' contém a configuração específica e obrigatória para cada código.
        'municipios' => [
            '4119152' => [ // Pinhais
                'homologacao'        => 'https://pinhais-hml.ipm.com.br:8443/ws_nfse',
                'producao'           => 'https://pinhais.ipm.com.br:8443/ws_nfse',
                'version'            => '1.30',
                'homologacao_soapns' => 'http://pinhais-hml.ipm.com.br',
                'producao_soapns'    => 'http://pinhais.ipm.com.br',
            ],
            '4212502' => [ // Penha
                'homologacao'        => 'https://penha-hml.ipm.com.br:9443/ws_nfse',
                'producao'           => 'https://penha.ipm.com.br:9443/ws_nfse',
                'version'            => '1.30',
                'homologacao_soapns' => 'http://penha-hml.ipm.com.br',
                'producao_soapns'    => 'http://penha.ipm.com.br',
            ],
        ]
    ];
}

//Municipios que utilizam padrao Ginfe
function getMunicipiosGinfe(): array
{
    return [
        ['Amparo', 'SP', '3501905'],
        ['Ananindeua', 'PA', '1500800'],
        ['Araraquara', 'SP', '3503208'],
        ['Bertioga', 'SP', '3506359'],
        ['Betim', 'MG', '3106705'],
        ['Campos dos Goytacazes', 'RJ', '3301009'],
        ['Capivari', 'SP', '3510401'],
        ['Caruaru', 'PE', '2604106'],
        ['Cataguases', 'MG', '3115300'],
        ['Colina', 'SP', '3512001'],
        ['Conceicao do Mato Dentro', 'MG', '3117504'],
        ['Contagem', 'MG', '3118601'],
        ['Diadema', 'SP', '3513801'],
        ['Embu-Guacu', 'SP', '3515103'],
        ['Franca', 'SP', '3516200'],
        ['Guararema', 'SP', '3518305'],
        ['Guaruja', 'SP', '3518701'],
        ['Guarulhos', 'SP', '3518800'],
        ['Hortolandia', 'SP', '3519071'],
        ['Itaborai', 'RJ', '3301900'],
        ['Itajuba', 'MG', '3132404'],
        ['Itauna', 'MG', '3133808'],
        ['Itu', 'SP', '3523909'],
        ['Jaboticabal', 'SP', '3524303'],
        ['Jardinopolis', 'SP', '3525102'],
        ['Jundiai', 'SP', '3525904'],
        ['Lagoa Santa', 'MG', '3137601'],
        ['Maceio', 'AL', '2704302'],
        ['Marechal Deodoro', 'AL', '2704708'],
        ['Marica', 'RJ', '3302700'],
        ['Matao', 'SP', '3529302'],
        ['Maua', 'SP', '3529401'],
        ['Mineiros', 'GO', '5213103'],
        ['Mococa', 'SP', '3530508'],
        ['Morro Agudo', 'SP', '3531902'],
        ['Muriae', 'MG', '3143906'],
        ['Olimpia', 'SP', '3533908'],
        ['Oliveira', 'MG', '3145604'],
        ['Para de Minas', 'MG', '3147105'],
        ['Paranagua', 'PR', '4118204'],
        ['Paulinia', 'SP', '3536505'],
        ['Porto Ferreira', 'SP', '3540705'],
        ['Pouso Alegre', 'MG', '3152501'],
        ['Registro', 'SP', '3542602'],
        ['Ribeirao Pires', 'SP', '3543303'],
        ['Ribeirao Preto', 'SP', '3543402'],
        ['Rio Bonito', 'RJ', '3304300'],
        ['Rio Claro', 'SP', '3543907'],
        ['Sacramento', 'MG', '3156908'],
        ['Salto', 'SP', '3545209'],
        ['Santarem', 'PB', '2513653'],
        ['Santarém', 'PA', '1506807'],
        ['Santo Andre', 'SP', '3547809'],
        ['Santos', 'SP', '3548500'],
        ['Sao Bernardo do Campos', 'SP', '3548708'],
        ['Sao Caetano do Sul', 'SP', '3548807'],
        ['Sao Carlos', 'SP', '3548906'],
        ['Sao Jose do Rio Preto', 'SP', '3549805'],
        ['Sao Roque', 'SP', '3550605'],
        ['Sao Sebastiao', 'SP', '3550704'],
        ['Suzano', 'SP', '3552502'],
        ['Taquaritinga', 'SP', '3553708'],
        ['Ubatuba', 'SP', '3555406'],
        ['Umuarama', 'PR', '4128104'],
        ['Varginha', 'MG', '3170701'],
        ['Votuporanga', 'SP', '3557105']
    ];
}

// COnfiguracoes Ginfe
function getConfigGinfes(): array
{
    return [
            'default' => [
                'homologacao'        => 'https://homologacao.ginfes.com.br/ServiceGinfesImpl',
                'producao'           => 'https://producao.ginfes.com.br/ServiceGinfesImpl',
                'version'            => '3',
                'homologacao_soapns' => 'http://homologacao.ginfes.com.br',
                'producao_soapns'    => 'http://producao.ginfes.com.br',
            ],
            'overrides' => [
                '3518800' => [ // Guarulhos
                    'producao'    => 'https://guarulhos.ginfes.com.br/ServiceGinfesImpl',
                ],
                '3106705' => [ // Betim
                    'producao' => 'https://betim.ginfes.com.br/ServiceGinfesImpl',
                ],
            ]
    ];
}



/**
 * Gera as configurações para os municípios, lidando com provedores
 * com e sem configurações padrão.
 *
 * @param string|null $codigoFiltro Código do município para filtrar (opcional)
 * @return array Configurações dos municípios
 */
function gerarConfiguracoes(?string $codigoFiltro = null): array
{
    $urls = [];

    $providers = [
        'IPM' => [
            'municipios' => getMunicipiosIpm(),
            'config'     => getConfigIPM(),
        ],
        'GINFES' => [
            'municipios' => getMunicipiosGinfe(),
            'config'     => getConfigGinfes(),
        ],
    ];

    foreach ($providers as $padrao => $provider) {
        // Pega a config padrão, se existir. Se não, usa um array vazio.
        $configDefault = $provider['config']['default'] ?? [];
        
        // Pega as configs específicas (pode ser 'overrides' ou 'municipios')
        $configsEspecificas = $provider['config']['overrides'] ?? $provider['config']['municipios'] ?? [];

        foreach ($provider['municipios'] as $mun) {
            [$nome, $uf, $codigo] = $mun;

            if ($codigoFiltro !== null && $codigoFiltro !== $codigo) {
                continue;
            }
            if (isset($urls[$codigo])) {
                continue;
            }

            // Pega a configuração específica para este município, se houver
            $configMunicipio = $configsEspecificas[$codigo] ?? null;
            
            // LÓGICA PRINCIPAL:
            // Se NÃO há config padrão (caso do IPM) E nenhuma config específica foi encontrada...
            if (empty($configDefault) && $configMunicipio === null) {
                // ...então este município não tem configuração. Avisa e pula.
                echo "AVISO: Configuração obrigatória não encontrada para o município '$nome' ($codigo) do padrão '$padrao'. O município será ignorado.\n";
                continue;
            }

            // Começa com a base (que pode ser a padrão ou vazia)
            $configFinal = $configDefault;
            
            // Se houver uma config específica, mescla por cima da base
            if ($configMunicipio !== null) {
                $configFinal = array_merge($configFinal, $configMunicipio);
            }
            
            // Adiciona os dados do município à configuração final
            $urls[$codigo] = array_merge($configFinal, [
                'municipio' => $nome,
                'uf'        => $uf,
                'padrao'    => $padrao,
            ]);
        }
    }
    
    return $urls;
}

// --- EXECUÇÃO ---
$codigoMunicipio = $argv[1] ?? null;
$configs = gerarConfiguracoes($codigoMunicipio);

$json = json_encode($configs, JSON_PRETTY_PRINT);

$storageDir = __DIR__ . '/../../bib/storage';

// se nao existir a pasta storage, ela sera criada
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

file_put_contents($storageDir . '/urls_webservices.json', $json);

echo "Arquivo gerado com sucesso.\n";