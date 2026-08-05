# Desafio Back-end PicPay

Este é o meu projeto resolvendo o famoso [Desafio Back-end do PicPay](https://github.com/PicPay/picpay-desafio-backend), construído com **Laravel**. 

A ideia do desafio é criar uma API REST simplificada de transferências de dinheiro entre usuários. Durante o desenvolvimento, aproveitei para focar bastante em **Clean Code** e em manter uma arquitetura organizada.

## O que foi implementado?

- **Configuração de API Limpa:** Removi toda a bagagem de frontend que vem por padrão no Laravel (Blade, Vite, NPM, etc.) para deixar o projeto focado 100% em ser uma API. Exceções e erros também foram configurados para retornar sempre em JSON.
- **Módulo de Usuários:** Criação de usuários (Comuns e Lojistas) com tratamento de dados. Por exemplo, o sistema limpa automaticamente os caracteres não numéricos (como pontos e traços) do CPF/CNPJ usando `FormRequests` antes de salvar.
- **Módulo de Carteiras (Wallets):** Relacionamento criado. A carteira é quem gerencia o saldo de cada usuário.
- **Módulo de Transferências:** É o coração da aplicação.
  - Validações de regras de negócio foram isoladas em `Rules` personalizadas do Laravel (ex: verificar se o usuário tem saldo, ou garantir que um usuário do tipo Lojista não faça transferências).
  - Toda a regra da transferência de dinheiro saiu do Controller e foi isolada no `TransferService`.
  - Uso de **Transações no Banco de Dados** (`DB::transaction`). Isso garante a consistência financeira: o dinheiro só sai de uma carteira e entra na outra se não houver nenhum erro no processo.
  - Padrão **Fail-Fast**: A aplicação verifica se os usuários (pagador e recebedor) existem logo de cara, retornando 404 antes mesmo de abrir a transação no banco.
  - As respostas de sucesso da API foram padronizadas (os dados retornam sempre encapsulados em uma chave `data`).

## Como rodar o projeto localmente

1. Clone o repositório.
2. Instale as dependências do PHP:
   ```bash
   composer install
   ```
3. Crie o arquivo de configuração e gere a chave da aplicação:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Rode as migrations para criar as tabelas no banco de dados (o Laravel já vem configurado para usar o SQLite por padrão na versão 11):
   ```bash
   php artisan migrate
   ```
5. Inicie o servidor local:
   ```bash
   php artisan serve
   ```

## Principais Padrões Utilizados
- **Conventional Commits**: Histórico de commits organizado e semântico.
- **Service Pattern**: Regras de negócio separadas dos Controllers.
- **Form Requests & Custom Rules**: Validação limpa e reutilizável.
