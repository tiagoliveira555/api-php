![Logo](https://github.com/tiagoliveira555/api-php/blob/main/public/screenshots/php_07.png)

# API Rest desenvolvida em PHP sem Framework

Esta API Rest foi desenvolvida em PHP sem a utilização de dependências externas, utilizando apenas PHP puro. Ela oferece várias funcionalidades, incluindo rotas dinâmicas, paginação, validação de entrada de dados, environment para guardar dados senvíveis e o uso de middlewares. Com esta API, é possível realizar operações básicas em um sistema de gerenciamento de usuários, como listar todos os usuários com paginação, visualizar informações de um usuário específico, criar novos usuários, atualizar e deletar.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)

## Principais Funções

1. Listar todos os usuários com paginação.
2. Listar apenas um usuário.
3. Criar um novo usuário.
4. Atualizar um usuário.
5. Deletar um usuário.

## Banco de Dados

```sql
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Como Usar

1. Clone o repositório: .
2. Navegue até o diretório `api-php`.
3. Renomei o arquivo `.env.example` para `.env` e coloque as informações do seu banco de dados.
4. Atualize o composer com `composer du`.
5. Inicie o servidor com `php -S localhost:8000 -t public`.
6. Acesse `http://localhost:8000/`

## EndPoints

**Listar todos os usuários com paginação**
```http
GET /users?per_page=4&page=6
```
![Listar Todos Usuários com paginação](https://github.com/tiagoliveira555/api-php/blob/main/public/screenshots/php_02.png)


**Visualizar um usuário**
```http
GET /users/{id}
```
Exemplo de requisição:
```http
PUT /users/32
```
![Listar um usuário específico](https://github.com/tiagoliveira555/api-php/blob/main/public/screenshots/php_03.png)

**Cadastrar novo usuário**
```http
POST /users
```
Exemplo de requisição:
```json
{
    "name": "Tiago Oliveira",
    "email": "tiago@mail.com",
    "password": "12345678"
}
```
![Novo Usuário](https://github.com/tiagoliveira555/api-php/blob/main/public/screenshots/php_04.png)

**Atualizar um usuário**
```http
PUT /users/{id}
```
Exemplo de requisição:
```http
PUT /users/32
```
```json
{
    "name": "Tiago Updated",
    "email": "tiagoupdated@mail.com",
    "password": "123456789"
}
```
![Atualizar Usuário](https://github.com/tiagoliveira555/api-php/blob/main/public/screenshots/php_05.png)

**Deletar um usuário**
```http
DELETE /users/{id}
```
Exemplo de requisição:
```http
DELETE /contas/32
```
![Deletar um Usuário](https://github.com/tiagoliveira555/api-php/blob/main/public/screenshots/php_06.png)

---

## Observações

- Certifique-se de ter o PHP e MySQL instalados em seu ambiente.
- Esta API foi desenvolvida com foco na simplicidade, utilizando apenas recursos nativos do PHP para demonstrar conceitos fundamentais de construção de APIs.
- Sinta-se à vontade para explorar, modificar e expandir esta API conforme suas necessidades específicas.
