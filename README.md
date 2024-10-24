
# Agenda de Contatos - Laravel API com Docker

Esta é uma API RESTful de uma agenda de contatos construída com Laravel, seguindo os padrões de projetos (Repository Pattern e Service Pattern) e utilizando Docker para execução. A aplicação permite adicionar, listar e buscar contatos por nome ou email, além de validar o CEP com uma API externa.

## Requisitos

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Configuração do Projeto

### 1. Clone o Repositório

```bash
git clone https://github.com/charlescleyton/agenda-contatos.git
cd agenda-contatos
```

### 2. Configuração do Ambiente

Copie o arquivo `.env.example` para `.env` e configure suas variáveis de ambiente:

```bash
cp .env.example .env
```

Ajuste as credenciais do banco de dados no arquivo `.env` para combinar com as configuradas no `docker-compose.yml`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### 3. Construir e Iniciar os Containers

Execute os comandos abaixo para construir e iniciar os containers da aplicação e do banco de dados MySQL:

```bash
docker-compose up --build -d
```

### 4. Instalar Dependências do Laravel

Dentro do container `app`, instale as dependências do Laravel:

```bash
docker-compose exec laravel bash 
composer install
```

### 5. Gerar a Chave da Aplicação

Execute o seguinte comando para gerar a chave da aplicação Laravel:

```bash
php artisan key:generate
```

### 6. Apagar cache

Execute o seguinte comando para cache que por ventura possam impedir a execução da aplicação:

```bash
php artisan config:cache
php artisan config:clear
```

### 7. Rodar as Migrações

Execute as migrações para criar as tabelas no banco de dados:

```bash
php artisan migrate
```

### 8. Populando o Banco de Dados (Opcional)

Você pode usar o Seeder para popular o banco de dados com dados falsos:

```bash
php artisan db:seed
```

## Endpoints da API

Aqui estão os principais endpoints da API:

### 1. Listar Todos os Contatos

**Endpoint**: `/api/contacts`  
**Método**: `GET`  
**Descrição**: Retorna uma lista de todos os contatos cadastrados.

Exemplo de requisição:

```bash
GET http://localhost:8080/api/contacts
```

### 2. Buscar Contatos por Nome ou Email

**Endpoint**: `/api/contacts?search?q={valor}`  
**Método**: `GET`  
**Descrição**: Busca contatos filtrando por nome ou email. O parâmetro `search` pode ser parte do nome ou email.

Exemplo de requisição:

```bash
GET http://localhost:8080/api/contacts/search?q=charles
```

### 3. Adicionar um Novo Contato

**Endpoint**: `/api/contacts`  
**Método**: `POST`  
**Descrição**: Adiciona um novo contato à agenda.

**Parâmetros**:
- `name` (string): Nome do contato.
- `phone` (string): Telefone do contato.
- `email` (string): Email do contato.
- `cep` (string): CEP do contato (será validado via API externa).

Exemplo de requisição:

```bash
POST http://localhost:8080/api/contacts
```

**Body** (exemplo usando `JSON`):

```json
{
    "name": "Charles Pereira",
    "phone": "(31) 77777-7777",
    "email": "charles@example.com",
    "cep": "30870-100"
}
```

Se o CEP for inválido, a API retornará:

```json
{
  "message": "Invalid CEP. Please provide a valid one."
}
```

### 4. Testes Automatizados

Para rodar os testes automatizados com PHPUnit:

```bash
docker-compose exec app php artisan test
```

## Comandos Úteis

- **Parar os containers**:
  ```bash
  docker-compose down
  ```

- **Acessar o container da aplicação**:
  ```bash
  docker-compose exec app bash
  ```

- **Acessar o MySQL via terminal**:
  ```bash
  docker-compose exec mysql mysql -u laravel -p
  ```

## Considerações Finais

Esta aplicação é um exemplo de uma API RESTful utilizando Docker, Laravel, com padrões de projeto Repository Pattern e Service Pattern e banco de dados MySQL. Certifique-se de que Docker e Docker Compose estejam instalados e configurados corretamente em sua máquina.

