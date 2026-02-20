## ControleConsultório — Sistema de Gestão Médica

Sistema **PHP 8 MVC** completo para gestão de clínica médica.

---

## 🚀 Setup rápido (produção)

### 1. Clonar repositório

```bash
git clone https://github.com/mareujo2012-blip/consultorio.git
cd consultorio
```

### 2. Instalar dependências PHP

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configurar `.env`

```bash
cp .env.example .env
# Editar conforme necessário
```

### 4. Criar diretórios necessários

```bash
mkdir -p public/uploads/photos public/uploads/logos logs/backups
chmod -R 755 public/uploads logs
```

### 5. Rodar migrations + seed

```bash
bash scripts/migrate.sh
RUN_SEED=true bash scripts/seed.sh
```

### 6. Deploy completo

```bash
bash scripts/run-all.sh "mensagem do commit"
```

---

## 📁 Estrutura do Projeto

```
consultorio/
├── app/
│   ├── Config/         # Database, App config
│   ├── Controllers/    # Auth, Dashboard, Patients, etc.
│   ├── Core/           # Router, BaseModel, BaseController
│   ├── Models/         # User, Patient, Appointment, etc.
│   └── Views/          # PHP views com TailwindCSS
├── bootstrap/          # app.php (session, headers, dispatch)
├── database/
│   ├── migrations/     # SQL migrations incrementais
│   └── seeds/          # Dados iniciais idempotentes
├── public/             # DocumentRoot do Apache
│   ├── .htaccess       # URL rewrite + HTTPS + segurança
│   ├── index.php       # Entry point
│   └── uploads/        # Fotos e logos (não versionado)
├── routes/
│   └── web.php         # Definição de todas as rotas
├── scripts/
│   ├── deploy.sh       # Deploy via FTP (lftp)
│   ├── migrate.sh      # Executa migrations pendentes
│   ├── seed.sh         # Dados iniciais
│   ├── git-push.sh     # Commit + push para GitHub
│   ├── rollback.sh     # Restaura backup do banco
│   └── run-all.sh      # Pipeline completo
├── logs/               # Logs e backups (não versionado)
├── .env                # Credenciais (não versionado)
└── composer.json
```

---

## 🔐 Segurança

- ✅ Senhas com **bcrypt** (cost 12)
- ✅ **CSRF token** em todos os formulários POST
- ✅ **PDO prepared statements** para todas as queries
- ✅ **Session regeneration** no login
- ✅ **Session timeout** automático (1h)
- ✅ **Math captcha** no login
- ✅ **HTTPS** obrigatório (redirect via .htaccess)
- ✅ **Security headers** (X-Frame-Options, XSS, HSTS, etc.)
- ✅ **Audit log** de login, criação de prontuário e PDFs
- ✅ HTML **escapado** em todas as saídas

---

## 📋 Módulos

| Módulo              | Funcionalidade                                       |
|---------------------|------------------------------------------------------|
| **Autenticação**    | Login seguro, captcha, CSRF, sessão                  |
| **Dashboard**       | KPIs, gráficos Chart.js                              |
| **Pacientes**       | CRUD, foto, busca, paginação                         |
| **Atendimentos**    | Criação, pagamento, notas                            |
| **Prontuário**      | Entradas imutáveis, SHA-256, timeline, PDF           |
| **Receitas**        | Editor, PDF com logo via Dompdf                      |
| **Financeiro**      | Relatórios por período, gráficos, ticket médio      |
| **Configurações**   | Dados do médico, senha, dados da clínica, logo       |
| **Audit Log**       | Rastreio de todas as ações sensíveis                 |

---

## 🛠️ Scripts de Deploy

| Script              | Descrição                                   |
|---------------------|----------------------------------------------|
| `run-all.sh`        | Pipeline completo (recomendado)              |
| `git-push.sh`       | Commit + push para o GitHub                  |
| `migrate.sh`        | Aplica migrations SQL pendentes              |
| `seed.sh`           | Insere dados iniciais (idempotente)          |
| `deploy.sh`         | Backup DB + migrate + FTP + validação HTTP   |
| `rollback.sh`       | Restaura último backup do banco              |

---

## 🔑 Acesso padrão (após seed)

- **URL:** https://consultorio.marcodaros.com.br
- **E-mail:** `admin@consultorio.marcodaros.com.br`
- **Senha:** `Admin@2026!`

> ⚠️ **Alterar a senha imediatamente após o primeiro login!**
