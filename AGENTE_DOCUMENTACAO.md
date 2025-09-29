# Documentação Estrutural do Projeto ADM v4.5

## Visão Geral
Este projeto é composto por diversos módulos organizados em subpastas, cada um responsável por uma área funcional do sistema ADM v4.5. A documentação técnica de cada módulo está localizada nas respectivas subpastas dentro de `class/`.

## Estrutura de Diretórios
```
/var/www/html/admv4.5/
├── class/           # Classes PHP e documentação técnica por módulo
│   ├── ped/         # Módulo de Pedidos
│   │   └── doc_tec_ped
│   ├── fin/         # Módulo Financeiro
│   │   └── doc_tec_fin
│   ├── est/         # Módulo de Estoque
│   │   └── doc_tec_est
│   ├── util/        # Utilitários
│   │   └── doc_tec_util
│   ├── crm/         # Módulo de CRM
│   │   └── doc_tec_crm.md
│   ├── cat/         # Módulo de Atendimento
│   │   └── doc_tec_cat
│   ├── coc/         # Módulo de Compras
│   │   └── doc_tec_coc
│   └── blt/         # Módulo de Boletos
│       └── doc_tec_blt
├── forms/           # Formulários PHP
├── js/              # Scripts JavaScript
├── template/        # Templates Smarty
├── css/             # Arquivos de estilo
├── boleto/          # Rotinas de boletos
├── bib/             # Bibliotecas auxiliares
└── doc/             # Documentação geral
```

## Documentação Técnica por Módulo
Cada subpasta em `class/` contém um arquivo de documentação técnica, por exemplo:
- `class/ped/doc_tec_ped` — Documentação do módulo de Pedidos
- `class/fin/doc_tec_fin` — Documentação do módulo Financeiro
- `class/est/doc_tec_est` — Documentação do módulo de Estoque
- `class/util/doc_tec_util` — Documentação de Utilitários
- `class/crm/doc_tec_crm.md` — Documentação do módulo CRM
- `class/cat/doc_tec_cat` — Documentação do módulo de Atendimento
- `class/coc/doc_tec_coc` — Documentação do módulo de Compras
- `class/blt/doc_tec_blt` — Documentação do módulo de Boletos

## Sugestão de Agente para Consulta
Um agente pode ser criado para:
- Responder dúvidas sobre arquitetura, fluxos e integrações do sistema
- Sugerir exemplos de uso das classes e fluxos de processos
- Navegar entre módulos e suas documentações
- Auxiliar desenvolvedores na manutenção e evolução do sistema

O agente pode ser implementado como um chatbot, CLI ou script, utilizando as documentações técnicas como base de conhecimento.

---

*Este arquivo foi gerado automaticamente para apoiar a navegação e consulta da estrutura do projeto ADM v4.5.* 