<p align="center">
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1200px-Laravel.svg.png" width="120" alt="Laravel Logo">
  <br>
  <h1 align="center">Desafio Back-end PicPay</h1>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
  <img src="https://img.shields.io/badge/Testing-PHPUnit-blue?style=for-the-badge&logo=phpunit" alt="PHPUnit">
</p>

## 💻 Sobre o Projeto

Esta é a minha solução para o famoso [Desafio Back-end do PicPay](https://github.com/PicPay/picpay-desafio-backend). O objetivo do desafio é criar uma API RESTful para realizar transferências de dinheiro entre usuários (Comuns e Lojistas), respeitando rigorosas regras de negócio.

Durante o desenvolvimento desta aplicação, o foco principal foi a construção de um código limpo, escalável, testável e de fácil manutenção, seguindo as melhores práticas do ecossistema Laravel e os princípios de **Clean Code** e **SOLID**.

---

## 🛠️ Arquitetura e Padrões de Projeto (Design Patterns)

Para garantir uma base sólida, a arquitetura do projeto foi desenhada visando o isolamento de responsabilidades:

- **Service Pattern**: Toda a lógica de negócios da transferência de valores foi extraída dos Controllers e centralizada na camada de serviço (`TransferService`), tornando os controllers enxutos e focados apenas em receber e responder requisições HTTP.
- **Data Transfer Objects (DTOs)**: Uso de DTOs (`TransferDTO`) para transportar dados de forma estruturada e tipada entre os Controllers e os Services.
- **Form Requests & Custom Rules**: A validação dos dados de entrada não ocorre nos Controllers. Utilizei `FormRequests` para validações comuns e **Custom Validation Rules** (ex: `CheckUserBalanceRule` e `CheckIfRetailerRule`) para regras de negócio específicas que requerem consultas ao banco de dados.
- **Database Transactions (`DB::transaction`)**: O núcleo financeiro do sistema. O dinheiro só é debitado de uma carteira e creditado na outra se todo o processo ocorrer sem erros, garantindo a integridade dos dados ACID (Atomicidade, Consistência, Isolamento, Durabilidade).
- **Fail-Fast**: A aplicação intercepta falhas o mais cedo possível (retornando `422` ou `404`) antes de processar lógicas pesadas.
- **Padrão API REST**: O Laravel foi otimizado para atuar como uma API pura. O tratamento global de exceções (no `bootstrap/app.php`) foi refinado para garantir que todos os erros, incluindo exceções inesperadas, sejam devolvidos em formato `JSON` padronizado.

---

## 🧪 Testes Automatizados

A qualidade do software é garantida por uma robusta suíte de testes automatizados (Testes Unitários e de Integração / Feature Tests). 

**O que foi testado:**
- Validações de requisições e regras de negócio complexas.
- Comportamento de endpoints (`UserController` e `TransferController`).
- Simulação de integrações externas utilizando `Http::fake()` para não comprometer a velocidade ou depender de rede externa durante a esteira de CI/CD.

Para executar os testes com suporte a **execução em paralelo** (super rápido graças ao pacote `brianium/paratest`):

```bash
php artisan test --parallel
```

---

## 🚀 Como executar o projeto localmente

Siga as instruções abaixo para rodar a aplicação em seu ambiente local:

### 1. Requisitos
- PHP 8.4+
- Composer
- Git

### 2. Passo a Passo

```bash
# Clone o repositório
git clone https://github.com/GabrielBacaf/Desafio-Back-end-PicPay.git

# Acesse a pasta do projeto
cd Desafio-Back-end-PicPay

# Instale as dependências do Composer
composer install

# Crie uma cópia do arquivo de configuração
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate

# Execute as migrations e popule o banco (Seeder) com dados falsos
php artisan migrate:fresh --seed

# Inicie o servidor local
php artisan serve
```

A API estará disponível em `http://localhost:8000`.

---

## 📄 Endpoints Principais

- `POST /api/v1/user` - Criação de novos usuários (Comum ou Lojista).
- `POST /api/v1/transfer` - Realização de transferências de saldo entre usuários.

---

## 🤝 Boas Práticas Adicionais

- **Conventional Commits**: Todo o histórico do Git segue o padrão semântico, facilitando o tracking de features e bugfixes.
- **Enums**: Utilização das novas Enums nativas do PHP 8.1+ (`TypeUsersEnum`) para evitar "magic strings" soltas pelo código.

---
> Desenvolvido com ☕ e foco em qualidade de código. 
