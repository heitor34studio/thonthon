
![logo-min](https://github.com/user-attachments/assets/539c7642-866c-4f92-ad3a-d120b9040c46)

# Thonthon
![PHP Badge](https://img.shields.io/badge/PHP-6c69f5?style=for-the-badge&logo=PHP&logoColor=white) ![MySQL Badge](https://img.shields.io/badge/MySQL-507ca4?style=for-the-badge&logo=MySQL&logoColor=white) ![CSS Badge](https://img.shields.io/badge/Css-0095ff?style=for-the-badge&logo=CSS3&logoColor=white) ![JS Badge](https://img.shields.io/badge/Javascript-fff200?style=for-the-badge&logo=Javascript&logoColor=black) ![JQuery Badge](https://img.shields.io/badge/JQuery-1f48b8?style=for-the-badge&logo=JQuery&logoColor=black) ![HTML Badge](https://img.shields.io/badge/HTML-ff6600?style=for-the-badge&logo=HTML5&logoColor=black) ![SCSS Badge](https://img.shields.io/badge/SCSS-ff00bf?style=for-the-badge&logo=Sass&logoColor=black)

O projeto [Thonthon](https://heitordutra.infinityfreeapp.com/thonthon/) é uma plataforma inspirada no Twitter que permite ao usuário criar sua conta, publicar sobre assuntos e interagir com publicações de outros usuários, bem como seguir eles.
*Esse projeto foi feito numa época em que eu ainda não havia entrado na faculdade para aprender sobre: Design Patterns, SOLID, Gof, GRASP, CRUD, Estrutura de Dados, ou Modelagem de Banco de Dados, entre outros. Então foi feito meio que de qualquer jeito essa parte da Estruturação, e o que eu estava mais focando era em fazer códigos funcionarem, já que era meu primeiro projeto.

## Índice 

* [Título e Descrição](#pesquisa-fipe)
* [Índice](#índice)
* [Funcionalidades do Projeto](#-funcionalidades-do-projeto)
* [Tecnologias Utilizadas](#%EF%B8%8F-técnicas-e-tecnologias-utilizadas)
* [Acesso ao Projeto](#-acesso-ao-projeto)
* [Abrir e Rodar o Projeto](#%EF%B8%8F-abrir-e-rodar-o-projeto)
* [Detalhamento do Código](#-detalhamento-do-código)

## 🔨 Funcionalidades do projeto

O Thonthon oferece as seguintes funcionalidades:

- Cadastro e login da sua conta.
- Confirmação via email.
- Publicação de postagens.
- Upload de sua própria imagem para perfil ou escolher uma das 5 padrões.
- Curtir suas publicações de outros usuários.
- Seguir outros usuários
- Pesquisa de usuários por username ou publicações por conteúdo.
- Copiar link de publicações, apagar suas publicações, ou copiar conteúdo da publicação.
- Alterar tema para clarou ou escuro.
- Alterar email e senha.
- Deletar conta.

## ✔️ Técnicas e tecnologias utilizadas

- `PHP`: Linguagem principal utilizada no desenvolvimento do projeto.
- `CSS`: Estilização das interfaces e responsividade para diferentes dispositivos.
- `JS & JQuery`: Utilizado para fazer a comunicação com o backend.
- `HTML`: Linguagem de marcação para estruturação das páginas.

## 📁 Acesso ao projeto

Você pode ver o projeto funcionando [aqui](https://heitordutra.infinityfreeapp.com/thonthon/).

## 🛠️ Abrir e rodar o projeto

Para abrir e rodar o projeto, siga os seguintes passos:

1. Baixe o xampp e clone o repositório na pasta `htdocs`.

2. Crie um banco de dados no `localhost/phpmyadmin` e altere os valores do arquivo `lib/db.class.php` para os respectivos do seu BD.

3. Acesse `http://localhost/`.

### Detalhamento do código:

O código PHP fornecido implementa um programa para cadastrar contas que podem fazer posts num sistema que simula uma plataforma de rede social.

#### 404, add, cadastrar, config, confirmation, home, index, login, post, profile e search.php
Arquivos que servindo de interfaces, funcionam como as páginas acessadas pelo usuário para interagir com a aplicação. Boa parte de seus códigos é em HTML, e o PHP é só usada como verificação para algum redirecionamento, ou verificações necessários antes de exibir o HTML na tela.

#### lib/crop, db.class, deleteit, email, general.class, likeConfig.class, usuario.class, verifica.class
Arquivos que contém os scripts, funções, regras de negócios, e fazem verificações, enviam emails, inserem e removem dados no Banco de Dados, e cuidam de toda lógica da aplicação.
*Esse projeto foi feito numa época em que eu ainda não havia entrado na faculdade para aprender sobre: Design Patterns, SOLID, Gof, GRASP, CRUD, Estrutura de Dados, ou Modelagem de Banco de Dados, entre outros. Então foi feito meio que de qualquer jeito essa parte da Estruturação, e o que eu estava mais focando era em fazer códigos funcionarem, já que era meu primeiro projeto.

### Exemplo de Uso
Ao executar o programa, o usuário pode escolher entre fazer Login ou Cadastrar um novo usuário. Após cadastrado e confirmado, o usuário faz login e é redirecionado para a home do programa, onde pode: ver seus posts recentes, junto com os posts das pessoas que segue; criar um novo post; pesquisar usuários ou posts; ver seu perfil; abrir as configurações; ou deslogar de sua conta.


https://github.com/user-attachments/assets/68bade99-ccfa-45c7-84b8-45e49bb62844


---

Este é o README atualizado para o projeto Thonthon. Ele fornece uma visão geral do projeto, suas funcionalidades, tecnologias utilizadas e como acessar e rodar o projeto. Para mais detalhes, você pode explorar os arquivos do código fonte mencionados.
