# Futebol na TV BOT

<p align="center">
<img src="https://raw.githubusercontent.com/jilcimar/football-schedule/master/public/icon.png" width="200" height="200">
<img src="https://raw.githubusercontent.com/jilcimar/football-schedule/master/public/telegram.png" width="200" height="200">
</p>

<p align="center">
<a href="https://github.com/jilcimar/futebol-na-tv-bot/issues"><img src="https://img.shields.io/github/issues/jilcimar/futebol-na-tv-bot" alt="Issues Open"></a>
<a><img src="https://img.shields.io/github/forks/jilcimar/futebol-na-tv-bot" alt="Forks"></a>
<a><img src="https://img.shields.io/github/stars/jilcimar/futebol-na-tv-bot" alt="Stars"></a>
<a><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
<a href="https://twitter.com/jilcimarfer"><img src="https://img.shields.io/twitter/url?style=social&url=http%3A%2F%2Ftwitter.com%2Fjilcimarfer" alt="Twitter"></a>
</p>

## O que é um Bot?
Conhecidos como bots – abreviação de robots, robôs em inglês – estes mecanismos são contas operadas por softwares que realizam
os mais variados serviços. [Fonte](http://abre.ai/oquebotfonte)

- [Acesse o Bot](https://t.me/futebolnatv_bot)
- [Sobre](#sobre)
- [Contribuição](#contribuicao)
- [Tecnologias](#tecnologias)
- [Excutando](#executando)

## Sobre
É um bot para Telegram que envia todos os dias em um horário programado
a lista de jogos dos principais campeonatos e onde será transmitido.

Os dados são obtidos através do site [futebolnatv.com.br](https://www.futebolnatv.com.br/) por meio
de [Web Scraping](https://www.scrapinghub.com/what-is-web-scraping/).
Foi criado um command que pode ser executado manualmente através do artisan, mas para esse projeto foi criado um job no servidor que faz a execução diária.

## Contribuição

Sinta-se a vontade para contribuir no repositório para melhorar alguma parte do código ou adicionar novas features.

## Tecnologias

Para o desenvolvimento foi utilzado:

- Laravel 7.x
    - [Laravel Facade for Goutte](https://github.com/dweidner/laravel-goutte)
    - [Telegram Bot API - PHP SDK](https://github.com/irazasyed/telegram-bot-sdk)
    - [Task Scheduling](https://laravel.com/docs/7.x/scheduling)
- [Telegram Bot API](https://core.telegram.org/bots/api)
- [Heroku](https://www.heroku.com/)
    - Usado para hospedar a aplicação e configurar o job.

## Executando

Para rodar o projeto é preciso:
#### Instalação das dependências

Para instalar as dependências do projeto o executável do composer deve estar disponível no PATH.
Caso esse requerimento seja satisfeito, basta rodar os seguintes comandos:

```bash
$ composer update
```
ou

```bash
$ composer install
```

#### Configuração do .env

Deve existir um arquivo `.env` no diretório raiz do projeto. Um arquivo `.env.example` é fornecido contendo as configurações
padrões do projeto (Copie o conteúdo do `.env.example` para dentro do `.env`).

É preciso rodar o seguinte comando para preencher o campo `APP_KEY` no `.env`.

 ```bash 
$ php artisan key:generate
```

Além disso, ainda no no `.env`. é
preciso inserir  as credenciais do seu Banco de Dados

### Migração para ciração do banco de dados

Os seguintes comandos devem ser executados no setup do projeto:

```bash
$ php artisan migrate
```

Caso as migrações já tenham sido executadas elas podem ser desfeitas com o seguinte comando:

```bash
$ php artisan migrate:rollback
```

## Executar o projeto

Para que o bot dispare as mensagens, é só rodar:

```bash
$  php artisan bot:cron
```
