# GynConect — Resumo Executivo

## Visão em Uma Página

**Nome do Projeto**: GynConect — Sistema de Gestão Comercial  
**Tipo**: Sistema Web + Progressive Web App (PWA)  
**Propósito**: Gestão comercial para distribuidores com equipe de vendas externa e modelo de consignação  
**Status**: Produção

---

## 📊 Visão Rápida

| Aspecto | Detalhes |
|---------|---------|
| **Stack** | PHP, MySQL, JavaScript Vanilla, CSS3 |
| **Arquitetura** | REST API + SPA (Single Page Application) |
| **Segurança** | Token-based auth, PDO, RBAC com 4 níveis |
| **Módulos** | 15+ módulos integrados |
| **Exportações** | PDF e Excel nativos em PHP |
| **Mobile** | PWA com suporte offline via Service Worker |

---

## 🎯 O Desafio

**Problema**: Distribuidores com equipes externas enfrentavam:
- Controle manual e descentralizado de estoque por vendedor
- Rastreamento impreciso de itens em consignação
- Ausência de visibilidade gerencial em tempo real
- Cálculo de comissões sujeito a erros
- Sem assinatura digital nos termos de consignação

**Solução**: Uma plataforma web completa que centraliza toda a operação, acessível em campo via PWA com suporte offline.

---

## ✨ Funcionalidades Principais

1. **🔐 Controle de Acesso** — 4 níveis de permissão (Vendedor, Gerente, Financeiro, Dev)
2. **📊 Dashboard** — KPIs em tempo real com exportação em PDF
3. **👥 Gestão de Clientes** — Cadastro, histórico, inatividade, WhatsApp
4. **🛒 Vendas** — Pedidos com assinatura digital e duas formas de pagamento
5. **📦 Consignação** — Controle com trilha de auditoria e termos assinados digitalmente
6. **🗃 Estoque** — Central + por vendedor + consignação, com alertas de mínimo
7. **💰 Comissões** — Cálculo automático por venda, pago pelo gerente
8. **🏦 Financeiro** — Controle de boletos com KPIs de vencimento
9. **🔄 Devoluções** — Quarentena e resolução com impacto automático no estoque
10. **📄 Relatórios** — PDF e Excel para vendas, romaneios e termos

---

## 🛠 Destaques Técnicos

### Frontend
- **JavaScript Vanilla** — SPA sem frameworks, máxima performance e controle
- **PWA com Service Worker** — uso offline em campo, essencial para vendedores externos
- **Padrão IIFE/Module Revealing** — organização modular sem bundler
- **Dark theme responsivo** — interface otimizada para uso mobile no campo

### Backend
- **PHP + PDO** — queries parametrizadas em toda a API, sem SQL injection
- **REST API modular** — endpoints organizados por domínio de negócio
- **Autenticação stateless** — token de sessão com longa duração
- **Exportações nativas** — PDF e Excel gerados em PHP, sem bibliotecas externas

### Segurança
- ✅ RBAC com 4 níveis — sem permissões cruzadas
- ✅ Queries parametrizadas — PDO em 100% dos endpoints
- ✅ Sessões controladas administrativamente
- ✅ Exportações protegidas por token

---

## 📈 Impacto e Resultados

- **Operação centralizada**: eliminou controles paralelos em planilhas
- **Visibilidade em tempo real**: gerente acompanha equipe e KPIs sem deslocamento
- **Redução de erros**: comissões e estoques calculados automaticamente
- **Campo conectado**: vendedores acessam e registram dados offline

---

## 🧠 O Que Foi Aprendido

### Técnico
- Desenvolvimento full-stack de ponta a ponta
- Design e implementação de API RESTful para contexto comercial
- PWA com estratégias de cache offline para uso em campo
- Design de banco de dados normalizado para modelo de negócio complexo
- Geração nativa de documentos (PDF/Excel) em PHP

### Arquitetura
- RBAC granular com módulos visíveis por nível de acesso
- Trilha de auditoria em operações críticas (consignação, estoque)
- Controle de versão da aplicação com invalidação automática de cache

---

## 🎨 Decisões de Design

**Por que SPA sem framework?**
- Zero dependências externas no core
- Carregamento mais rápido — crítico para conexões de campo
- Controle total sobre o comportamento da aplicação

**Por que PWA e não app nativo?**
- Distribuição imediata — sem app store
- Cross-platform — Android e iOS com o mesmo código
- Atualização automática — sem processo de review

**Por que PHP com exportações nativas?**
- Hospedagem compartilhada comum — compatibilidade total
- PDF e Excel sem biblioteca externa = sem dependência de terceiros
- Performance previsível em servidor simples

---

## 🏆 Complexidade do Projeto

| Métrica | Estimativa |
|---------|-----------|
| **Módulos** | 15+ módulos integrados |
| **Níveis de Acesso** | 4 (Vendedor, Gerente, Financeiro, Dev) |
| **Tipos de Exportação** | PDF, Excel, Romaneio, Termo de Consignação |
| **Tipos de Estoque** | Central, por Vendedor, Consignação |
| **Status de Venda** | Pendente → Confirmada → Entregue |
| **Status de Devolução** | Registrada → Aprovada → Quarentena → Resolvida |

---

## 🎬 Pitch em 30 Segundos

> "Desenvolvi o GynConect, um sistema web de gestão comercial completo para distribuidores com equipes de vendas externas. A plataforma controla clientes, estoque, vendas, consignação, comissões e financeiro — tudo em uma SPA construída com PHP e JavaScript Vanilla, funcionando como PWA com suporte offline em campo. O sistema tem 4 níveis de acesso, assinatura digital nos pedidos e termos de consignação, exportação nativa de PDF e Excel, e está em produção atendendo uma operação real."

---

## 📊 Stats Rápidas

```
⚙️  Arquitetura:  REST API + SPA
📱  Mobile:       PWA + Service Worker (offline)
🔐  Segurança:   RBAC 4 níveis + PDO + tokens
📦  Módulos:     15+ integrados
📄  Exportações: PDF + Excel (PHP nativo)
🚀  Status:      Produção
```

---

## 🔗 Navegação

- [📖 README Principal](README.md)
- [🇺🇸 English Version](README-EN.md)
- [🏗 Arquitetura Detalhada](docs/ARQUITETURA.md)
- [🔌 Documentação da API](docs/API.md)
- [🔒 Política de Segurança](SECURITY.md)
- [📋 Changelog](CHANGELOG.md)

---

<div align="center">

**© 2025-2026 Lucas (Erl Dev)**

*Sistema proprietário — documentação de portfólio apenas*

</div>
