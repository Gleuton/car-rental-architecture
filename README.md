# Locadora de Carros

Este projeto é uma aplicação de locadora de carros desenvolvida com **Laravel 12**, apresentando rotas de API e um frontend integrado com **Vue.js**.

## 🚀 Sobre o Projeto

Este repositório foi criado com o propósito específico de estudar e praticar conceitos de arquitetura de software.

### ⚠️ Over-Engineering Proposital

O projeto sofre propositalmente de **over-engineering**. Dado o contexto de um CRUD simples, a complexidade adicionada seria desnecessária em um cenário de produção comum. No entanto, essa abordagem foi escolhida para:
- Praticar conceitos de **Clean Architecture** e **Domain-Driven Design (DDD)**.
- Estudar a separação de responsabilidades em camadas.
- Facilitar a implementação de testes unitários e de integração em uma estrutura desacoplada.
- Experimentar com padrões de projeto que garantem escalabilidade e manutenibilidade em sistemas complexos.

## 🛠️ Tecnologias Utilizadas

- **Backend:** Laravel 12 (PHP 8.5+)
- **Frontend:** Vue.js
- **Banco de Dados:** PostgreSQL / SQLite (suporte via Docker)
- **Testes:** Pest PHP
- **Ferramentas:** Docker Compose, Vite

## 🏗️ Arquitetura

A aplicação segue uma estrutura inspirada em camadas (Layered Architecture/Clean Architecture):

- **App/Core**: Contém a lógica de negócio central, independente de frameworks.
    - **Domain**: Entidades, interfaces de repositório, exceções de domínio e regras de negócio.
    - **Application**: Casos de uso (Use Cases) e DTOs.
    - **Infra**: Implementações concretas de repositórios, serviços externos e persistência (Eloquent).
- **App/Http**: Camada de entrada (Controllers, Requests, Resources) que faz a ponte entre o mundo externo e a camada de aplicação.

## 🏁 Como Executar

### Pré-requisitos
- Docker & Docker Compose

### Instalação

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/app_locadora_carros.git
cd app_locadora_carros
```

2. Crie o arquivo `.env`:
```bash
cp .env.example .env
```

3. Instale as dependências do PHP:
```bash
docker compose -f docker-compose.dev.yml run --rm locadora-app composer install
```

4. Suba os containers:
```bash
docker compose -f docker-compose.dev.yml up -d
```

5. Gere a chave da aplicação:
```bash
docker compose -f docker-compose.dev.yml exec locadora-app php artisan key:generate
```

6. Execute as migrations:
```bash
docker compose -f docker-compose.dev.yml exec locadora-app php artisan migrate
```

7. Instale as dependências do frontend e inicie o servidor de desenvolvimento:
```bash
docker compose -f docker-compose.dev.yml exec locadora-app npm install
docker compose -f docker-compose.dev.yml exec locadora-app npm run dev
```

## 🧪 Testes

Para rodar os testes com Pest:
```bash
docker compose -f docker-compose.dev.yml exec locadora-app php artisan test
```
