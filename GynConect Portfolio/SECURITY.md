# Política de Segurança — GynConect

## Visão Geral

Este documento descreve as práticas e medidas de segurança implementadas no GynConect. Nenhum dado sensível de produção, credenciais ou configuração de servidor está incluído neste portfólio.

---

## 🔐 Autenticação

- **Mecanismo**: Token de sessão stateless com longa duração
- **Armazenamento**: Token enviado via cabeçalho HTTP em todas as requisições autenticadas
- **Expiração**: Tokens com validade controlada e renovação automática em sessões ativas
- **Primeiro Acesso**: Troca de senha obrigatória no primeiro login
- **Recuperação**: Fluxo de recuperação de senha via e-mail com token temporário

---

## 🛡 Controle de Acesso (RBAC)

GynConect implementa **Role-Based Access Control** com quatro níveis:

| Nível | Acesso |
|-------|--------|
| **Vendedor** | Próprios clientes, vendas e comissões apenas |
| **Gerente** | Equipe completa, relatórios, aprovações, despesas |
| **Financeiro** | Módulos financeiros (boletos, comissões) |
| **Dev** | Acesso total incluindo painel administrativo |

- Cada módulo valida o nível de acesso antes de processar qualquer requisição
- Sem herança ou permissões cruzadas entre níveis
- Nível Dev não gera comissão nem custo contábil para a empresa

---

## 🗄 Banco de Dados

- **Driver**: PDO (PHP Data Objects)
- **Queries**: 100% parametrizadas — SQL injection eliminado por design
- **Credenciais**: Nunca expostas no frontend ou no código versionado
- **Conexão**: Configuração isolada em arquivo não versionado

---

## 🌐 Segurança de Sessão

- **Controle administrativo**: Gerente e Dev podem visualizar todas as sessões ativas
- **Encerramento**: Sessões podem ser terminadas individualmente ou em massa
- **Auditoria**: Feed de atividade recente no painel Dev para rastreamento de ações

---

## 📄 Exportações

- Todos os endpoints de exportação (PDF, Excel) requerem token de acesso válido
- Geração server-side — nenhum dado é processado no cliente
- Filtros de acesso aplicados antes da geração do documento

---

## 🔒 Práticas Gerais

- **Headers de segurança**: `X-Content-Type-Options: nosniff` e `Referrer-Policy` configurados
- **HTTPS**: Conexão criptografada obrigatória em produção
- **Senhas**: Hash seguro antes do armazenamento
- **Dados sensíveis**: CPF/CNPJ e dados de contato protegidos por nível de acesso

---

## 📢 Reporte de Vulnerabilidades

Este é um repositório de portfólio — o código-fonte não está disponível publicamente.

Para contato sobre segurança ou o projeto:
- 🌐 [erldev.com.br](https://erldev.com.br)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

<div align="center">

*GynConect — Sistema proprietário. Documentação de portfólio apenas.*

</div>
