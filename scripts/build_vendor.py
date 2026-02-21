#!/usr/bin/env python3
"""
build_vendor.py
===============
Baixa dompdf e dependencias do GitHub, gera vendor/ com autoloader PSR-4,
e faz upload via FTP para o servidor.
Nao requer PHP nem Composer instalados localmente.

EXECUTAR: python scripts/build_vendor.py
"""
import ftplib
import os
import sys
import time
import zipfile
import urllib.request
import shutil

FTP_HOST = '187.110.162.234'
FTP_PORT = 21
FTP_USER = 'consultorio@marcodaros.com.br'
FTP_PASS = '90860Placa8010@#$'
FTP_ROOT = '/public_html/consultorio'

ROOT_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TMP_DIR  = os.path.join(ROOT_DIR, '_tmp_vendor_build')

# Pacotes a baixar via API do GitHub
PACKAGES = [
    {
        'name':   'dompdf/dompdf',
        'url':    'https://api.github.com/repos/dompdf/dompdf/zipball/v3.1.4',
        'target': 'dompdf/dompdf',
    },
    {
        'name':   'phenx/php-font-lib',
        'url':    'https://api.github.com/repos/dompdf/php-font-lib/zipball/1.0.2',
        'target': 'phenx/php-font-lib',
    },
    {
        'name':   'phenx/php-svg-lib',
        'url':    'https://api.github.com/repos/dompdf/php-svg-lib/zipball/1.0.2',
        'target': 'phenx/php-svg-lib',
    },
    {
        'name':   'sabberworm/php-css-parser',
        'url':    'https://api.github.com/repos/sabberworm/PHP-CSS-Parser/zipball/8.4.0',
        'target': 'sabberworm/php-css-parser',
    },
    {
        'name':   'masterminds/html5',
        'url':    'https://api.github.com/repos/Masterminds/html5-php/zipball/2.8.0',
        'target': 'masterminds/html5',
    },
]

def log(msg):
    print(f"[{time.strftime('%H:%M:%S')}] {msg}", flush=True)

# ── Download ────────────────────────────────────────────────────────────────
def download_zip(url, dest_path):
    log(f"  GET {url}")
    req = urllib.request.Request(url, headers={
        'User-Agent': 'Mozilla/5.0',
        'Accept': 'application/zip',
    })
    with urllib.request.urlopen(req, timeout=120) as resp:
        data = resp.read()
    with open(dest_path, 'wb') as f:
        f.write(data)
    log(f"  OK  {len(data)//1024} KB -> {os.path.basename(dest_path)}")

def extract_zip(zip_path, target_dir):
    """Extrai zip ignorando o diretório raiz interno (ex: dompdf-dompdf-abc123/)"""
    with zipfile.ZipFile(zip_path, 'r') as zf:
        names = zf.namelist()
        # Descobrir prefixo raiz automaticamente
        root_prefix = names[0].split('/')[0] + '/'
        for member in names:
            if not member.startswith(root_prefix):
                continue
            relative = member[len(root_prefix):]
            if not relative:
                continue
            dest = os.path.join(target_dir, relative)
            if member.endswith('/'):
                os.makedirs(dest, exist_ok=True)
            else:
                os.makedirs(os.path.dirname(dest), exist_ok=True)
                with zf.open(member) as src, open(dest, 'wb') as dst:
                    dst.write(src.read())

# ── FTP ─────────────────────────────────────────────────────────────────────
def ftp_mkdirs(ftp, remote_dir):
    parts = remote_dir.replace('\\', '/').split('/')
    cur = ''
    for part in parts:
        if not part:
            continue
        cur = cur + '/' + part
        try:
            ftp.mkd(cur)
        except ftplib.error_perm:
            pass

def upload_dir_ftp(ftp, local_dir, remote_dir, counter):
    for item in sorted(os.listdir(local_dir)):
        local_path  = os.path.join(local_dir, item)
        remote_path = remote_dir.rstrip('/') + '/' + item
        if os.path.isdir(local_path):
            ftp_mkdirs(ftp, remote_path)
            upload_dir_ftp(ftp, local_path, remote_path, counter)
        else:
            try:
                with open(local_path, 'rb') as f:
                    ftp.storbinary(f'STOR {remote_path}', f, blocksize=32768)
                counter[0] += 1
                if counter[0] % 50 == 0:
                    log(f"  ... {counter[0]} arquivos enviados")
            except Exception as e:
                log(f"  WARN {item}: {e}")

# ── Gerar autoload ──────────────────────────────────────────────────────────
def write_file(path, content):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8', newline='\n') as f:
        f.write(content)

def generate_autoloader(vendor_dir):
    composer_dir = os.path.join(vendor_dir, 'composer')
    os.makedirs(composer_dir, exist_ok=True)

    # PSR-4 map
    psr4 = {
        'App\\\\':      "array($baseDir . '/app')",
        'Dompdf\\\\':   "array($vendorDir . '/dompdf/dompdf/src', $vendorDir . '/dompdf/dompdf/lib')",
        'FontLib\\\\':  "array($vendorDir . '/phenx/php-font-lib/src/FontLib')",
        'Svg\\\\':      "array($vendorDir . '/phenx/php-svg-lib/src/Svg')",
        'Sabberworm\\\\CSS\\\\': "array($vendorDir . '/sabberworm/php-css-parser/src')",
        'Masterminds\\\\': "array($vendorDir . '/masterminds/html5/src')",
    }

    psr4_lines = '\n'.join(
        f"    '{ns}' => {path},"
        for ns, path in psr4.items()
    )

    write_file(os.path.join(composer_dir, 'autoload_psr4.php'),
        "<?php\n$vendorDir = dirname(__DIR__);\n$baseDir = dirname($vendorDir);\nreturn array(\n"
        + psr4_lines + "\n);\n"
    )
    write_file(os.path.join(composer_dir, 'autoload_namespaces.php'),
        "<?php\n$vendorDir = dirname(__DIR__);\n$baseDir = dirname($vendorDir);\nreturn array();\n"
    )
    write_file(os.path.join(composer_dir, 'autoload_classmap.php'),
        "<?php\n$vendorDir = dirname(__DIR__);\n$baseDir = dirname($vendorDir);\nreturn array();\n"
    )
    write_file(os.path.join(composer_dir, 'autoload_files.php'),
        "<?php\n$vendorDir = dirname(__DIR__);\n$baseDir = dirname($vendorDir);\nreturn array();\n"
    )

    write_file(os.path.join(composer_dir, 'ClassLoader.php'), r"""<?php
namespace Composer\Autoload;

class ClassLoader
{
    private $prefixLengthsPsr4 = [];
    private $prefixDirsPsr4    = [];
    private $classMap          = [];

    public function addPsr4($prefix, $paths)
    {
        if (!is_array($paths)) { $paths = [$paths]; }
        if ($prefix) {
            $length = strlen($prefix);
            $this->prefixLengthsPsr4[$prefix[0]][$prefix] = $length;
            if (!isset($this->prefixDirsPsr4[$prefix])) {
                $this->prefixDirsPsr4[$prefix] = $paths;
            } else {
                $this->prefixDirsPsr4[$prefix] = array_merge($this->prefixDirsPsr4[$prefix], $paths);
            }
        }
    }

    public function addClassMap(array $classMap)
    {
        $this->classMap = array_merge($this->classMap, $classMap);
    }

    public function register($prepend = false)
    {
        spl_autoload_register([$this, 'loadClass'], true, $prepend);
    }

    public function loadClass($class)
    {
        if ($file = $this->findFile($class)) {
            include $file;
            return true;
        }
        return false;
    }

    public function findFile($class)
    {
        if (isset($this->classMap[$class])) {
            return $this->classMap[$class];
        }
        $first = $class[0];
        if (isset($this->prefixLengthsPsr4[$first])) {
            $subPath = $class;
            while (false !== $lastPos = strrpos($subPath, '\\')) {
                $subPath = substr($subPath, 0, $lastPos);
                $search  = $subPath . '\\';
                if (isset($this->prefixDirsPsr4[$search])) {
                    $pathEnd = DIRECTORY_SEPARATOR
                        . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, $lastPos + 1))
                        . '.php';
                    foreach ($this->prefixDirsPsr4[$search] as $dir) {
                        if (file_exists($file = $dir . $pathEnd)) {
                            return $file;
                        }
                    }
                }
            }
        }
        return false;
    }
}
""")

    write_file(os.path.join(composer_dir, 'autoload_real.php'), r"""<?php

class ComposerAutoloaderInitConsultorio
{
    private static $loader;

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/ClassLoader.php';

        $loader = new \Composer\Autoload\ClassLoader();

        $map = require __DIR__ . '/autoload_psr4.php';
        foreach ($map as $namespace => $paths) {
            $loader->addPsr4($namespace, $paths);
        }

        $classMap = require __DIR__ . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        $loader->register(true);

        self::$loader = $loader;
        return $loader;
    }
}
""")

    write_file(os.path.join(vendor_dir, 'autoload.php'), r"""<?php

// autoload.php gerado pelo build_vendor.py
require_once __DIR__ . '/composer/autoload_real.php';

return ComposerAutoloaderInitConsultorio::getLoader();
""")

    log("  autoload.php e composer/ gerados com sucesso")

# ── MAIN ─────────────────────────────────────────────────────────────────────
if __name__ == '__main__':
    log('=' * 60)
    log('BUILD E UPLOAD DO vendor/')
    log('=' * 60)

    # Limpar tmp
    if os.path.exists(TMP_DIR):
        shutil.rmtree(TMP_DIR)
    os.makedirs(TMP_DIR)

    vendor_dir = os.path.join(TMP_DIR, 'vendor')
    os.makedirs(vendor_dir)

    # STEP 1: Baixar e extrair pacotes
    log('\nSTEP 1: Baixando pacotes do GitHub...')
    for pkg in PACKAGES:
        log(f"\n  [{pkg['name']}]")
        zip_path   = os.path.join(TMP_DIR, pkg['name'].replace('/', '_') + '.zip')
        target_dir = os.path.join(vendor_dir, *pkg['target'].split('/'))
        os.makedirs(target_dir, exist_ok=True)

        try:
            download_zip(pkg['url'], zip_path)
            extract_zip(zip_path, target_dir)
            os.remove(zip_path)
            log(f"  Extraido -> vendor/{pkg['target']}")
        except Exception as e:
            log(f"  ERRO: {e}")
            shutil.rmtree(TMP_DIR, ignore_errors=True)
            sys.exit(1)

    # STEP 2: Gerar autoloader
    log('\nSTEP 2: Gerando autoloader PSR-4...')
    generate_autoloader(vendor_dir)

    # STEP 3: Upload FTP
    log('\nSTEP 3: Conectando ao FTP...')
    ftp = ftplib.FTP()
    ftp.connect(FTP_HOST, FTP_PORT, timeout=60)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.set_pasv(True)
    log('  FTP conectado!')

    remote_vendor = FTP_ROOT + '/vendor'
    ftp_mkdirs(ftp, remote_vendor)

    counter = [0]
    start   = time.time()

    log(f'  Enviando vendor/ para {remote_vendor}...')
    upload_dir_ftp(ftp, vendor_dir, remote_vendor, counter)

    elapsed = time.time() - start
    ftp.quit()

    log(f'\n  Upload: {counter[0]} arquivo(s) em {elapsed:.1f}s')

    # Limpar tmp
    shutil.rmtree(TMP_DIR, ignore_errors=True)

    log('\n' + '=' * 60)
    log('CONCLUIDO! Acesse:')
    log('https://consultorio.marcodaros.com.br')
    log('=' * 60)
