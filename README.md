# Alpes One – Campanhas (WinterCMS)

Repositório do **plugin + tema** para criação de landing pages de campanhas com **header/footer padronizados**, **galeria/carrossel**, **destaques** e **formulário de cotação** no banner. Projeto montado para avaliação sênior: organização por componentes, partials, assets estáticos e formulários no backend (WinterCMS).

---

## Sumário
- [Stack](#stack)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Execução](#execução)
- [Como usar (fluxo de campanha)](#como-usar-fluxo-de-campanha)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Personalização (onde editar)](#personalização-onde-editar)
- [Scripts úteis](#scripts-úteis)
- [Boas práticas e convenções](#boas-práticas-e-convenções)
- [Licença](#licença)

---

## Stack
- **PHP 8.1+**
- **WinterCMS** (baseado em Laravel)
- **Composer**
- **SQLite** (dev) ou **MySQL/MariaDB** (prod)
- **Tailwind (CDN)** para o front rápido
- **Twig** (templates/partials)
- **YAML** (configuração de forms e listas no backend)

---

## Requisitos
- PHP 8.1+ com extensões:
  - `mbstring`, `pdo`, `pdo_sqlite` (ou `pdo_mysql`), `openssl`, `json`, `curl`, **`gd`**
- Composer instalado e no `PATH` (Windows)

---

## Instalação

```bash
# 1) Clonar
git clone https://github.com/hugogalli/alpesone-campanhas.git
cd alpesone-campanhas

# 2) Dependências PHP
composer install

# 3) Ambiente
cp .env.example .env
# Edite .env:
# - APP_URL=http://localhost:8000
# - DB_CONNECTION=sqlite
# - DB_DATABASE=database/database.sqlite   (crie o arquivo vazio)
mkdir -p database && type NUL > database/database.sqlite  # (Windows)
# ou: touch database/database.sqlite                       # (Linux/Mac)

# 4) Chave da app
php artisan key:generate

# 5) Migrações de Winter e do plugin
php artisan winter:up
# se estiver iterando no plugin:
# php artisan plugin:refresh Alpes.Campaigns

# 6) Usuário do backend (se ainda não existir)
php artisan backend:user
# siga o prompt (email, senha, etc.)
```

---

## Execução

```bash
php artisan serve
# acesse: http://localhost:8000

# Backend (painel administrativo):
# http://localhost:8000/backend
```

Login com o usuário criado via `php artisan backend:user`.

---

## Como usar (fluxo de campanha)

1. **Crie/edite uma Campanha** no backend:
   - Menu **Campanhas** → **Nova Campanha**
   - Campos:
     - **Geral**: `name`, `slug`, `is_active`, `meta_title`, `meta_description`
     - **Branding**: `brand[logo]` (logo opcional por campanha; se vazio, cai na logo padrão do tema)
     - **Seção 1 (Banner)**: título, subtítulo, `banner_desktop`, `banner_mobile`
     - **Seção 2 (Galeria)**: título, subtítulo, **repeater** de imagens com **Legenda** (aparece como caption do slide)
     - **Seção 3 (Destaques)**: até **3** destaques (repeater limitado) com imagem, título e descrição
2. **Página pública**: acessa via `/:slug` (ex.: `http://localhost:8000/primeira-campanha`)
3. **Header/Footer**: são **globais** no tema. A logo do header usa a da campanha (se houver) ou a **padrão** do tema.

---

## Estrutura do projeto

```
alpesone-campanhas/
├─ app/                  # app Laravel/Winter
├─ modules/              # módulos do WinterCMS
├─ plugins/
│  └─ alpes/campaigns/
│     ├─ Plugin.php
│     ├─ updates/
│     │  ├─ create_campaigns_table.php
│     │  └─ version.yaml
│     ├─ models/
│     │  └─ campaign/
│     │     ├─ columns.yaml
│     │     └─ fields.yaml
│     ├─ controllers/
│     │  └─ campaigns/
│     │     ├─ Campaigns.php
│     │     ├─ config_form.yaml
│     │     ├─ config_list.yaml
│     │     └─ _list_toolbar.htm
│     ├─ components/
│     │  └─ campaignpage/
│     │     ├─ CampaignPage.php
│     │     ├─ default.htm          # só inclui partials
│     │     ├─ banner.htm
│     │     ├─ gallery.htm
│     │     └─ highlights.htm
│     └─ assets/
│        ├─ css/campaign.css
│        └─ js/campaign.js
└─ themes/
   └─ seu_tema/
      ├─ layouts/default.htm        # inclui {% styles %} e {% scripts %}
      ├─ partials/site/header.htm   # logo dinâmica (campanha ou padrão)
      └─ partials/site/footer.htm   # formulário de contato global + copyright
```

---

## Personalização (onde editar)

### Backend (formularios/colunas)
- `plugins/alpes/campaigns/models/campaign/fields.yaml`: adiciona/edita campos do form
- `plugins/alpes/campaigns/models/campaign/columns.yaml`: colunas da listagem
- `plugins/alpes/campaigns/controllers/campaigns/config_form.yaml`: configuração do FormController
- `plugins/alpes/campaigns/controllers/campaigns/config_list.yaml`: configuração da lista

### Frontend (páginas/partials/estilo)
- **Componente da campanha**:
  - `components/campaignpage/banner.htm`: banner + título/subtítulo + formulário do banner (desktop-only)
  - `components/campaignpage/gallery.htm`: carrossel com legendas e CTA
  - `components/campaignpage/highlights.htm`: destaques verticais alternando layout (imagem/texto)
  - `assets/css/campaign.css`: utilitários (sombra, botão padrão, inputs sublinhados, botões do carrossel)
  - `assets/js/campaign.js`: inicialização do carrossel via atributos `data-`
- **Tema**:
  - `themes/seu_tema/layouts/default.htm`: **obrigatório** conter `{% styles %}` no `<head>` e `{% scripts %}` antes de `</body>`
  - `themes/seu_tema/partials/site/header.htm`: header global; usa `campaign.brand.logo` ou `assets/images/logo-default.svg`
  - `themes/seu_tema/partials/site/footer.htm`: footer global com formulário leve e copyright

### Ativação dos assets do plugin
No `CampaignPage.php` (componente), em `onRun()`:
```php
$this->addCss('/plugins/alpes/campaigns/assets/css/campaign.css');
$this->addJs('/plugins/alpes/campaigns/assets/js/campaign.js');
```
E **no layout** do tema:
```twig
{% styles %}     {# dentro do <head> #}
...
{% scripts %}    {# antes de </body> #}
```

---

## Scripts úteis

```bash
# Reaplicar migrations do plugin (bom para desenvolvimento)
php artisan plugin:refresh Alpes.Campaigns

# Subir tudo (Winter + plugins)
php artisan winter:up

# Limpar caches
php artisan cache:clear
php artisan config:clear
composer dump-autoload

# Criar/editar usuário do backend
php artisan backend:user
```

---

## Boas práticas e convenções

- **Campos JSON** (`section1`, `section2`, `section3`, `brand`) são `text` no banco e mapeados via `$jsonable` no Model → compatível com SQLite/MySQL.
- **Máximo de 3 destaques** controlado no `repeater` (campo `maxItems: 3`).
- **Carrossel sem libs**: `data-carousel`, `[data-track]`, `[data-prev]/[data-next]` e `data-caption`.
- **Layout limpo**: banner/gallery/highlights separados em **partials**, `default.htm` só orquestra.
- **Tailwind via CDN**: reduz atrito de build no teste técnico; em produção, migrar para build dedicado.
- **Header/Footer globais**: campanhas só trocam a **logo** se quiserem; existe **logo padrão** no tema.

---

**Resetar migrations do plugin**
```bash
php artisan plugin:refresh Alpes.Campaigns
```

---

## Licença
Este projeto é disponibilizado apenas para avaliação técnica.  
Para uso comercial/derivações, alinhar termos previamente.
