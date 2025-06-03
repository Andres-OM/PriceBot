# PriceBot - Aplicación de Comparación de Precios

PriceBot es una aplicación web que permite recopilar los precios de productos de tiendas online y compararlos con los de la competencia para ayudar a ajustar los precios de una tienda propia y ser competitivo en el mercado.

## Despliegue de la Aplicación Web

Siga estos pasos para configurar y ejecutar el proyecto PriceBot en su entorno local.

### 1. Requisitos Previos

Asegúrese de tener instalados los siguientes componentes en su sistema:

*   **PHP (versión 8.1.0 o superior):**
    *   Descargar desde: [PHP Archives](https://windows.php.net/downloads/releases/archives/) (buscar la versión 8.1.x).
    *   **Configuración del PATH:** Añadir la ruta a la carpeta de PHP (ej. `C:\php-8.1.0`) a la variable de entorno PATH de su sistema.
    *   **Archivo `php.ini`:**
        1.  Localice el archivo `php.ini-development` en su carpeta de instalación de PHP.
        2.  Renómbrelo a `php.ini`.
        3.  **Importante:** Revise y descomente las extensiones necesarias para Laravel y el proyecto (ej. `pdo_mysql`, `mbstring`, `curl`, `openssl`, `gd`, `fileinfo`, `xml`, `zip`, `intl`, `bcmath`, `exif`, `sodium`). Puede usar como referencia el archivo `php.ini` incluido en este proyecto si lo proporciona con las dependencias ya modificadas, o configurar uno nuevo.

*   **Node.js (versión LTS recomendada):**
    *   Descargar desde: [Node.js Oficial](https://nodejs.org/es).
    *   Durante la instalación, asegúrese de que Node.js y npm se añadan al PATH. `npm` se instala automáticamente con Node.js.

*   **Composer (Gestor de dependencias PHP):**
    *   Descargar e instalar desde: [GetComposer.org](https://getcomposer.org/download/).
    *   Durante la instalación, vincúlelo con su ejecutable de PHP 8.1.x.

*   **Python (versión 3.x):**
    *   Descargar desde: [Python.org Downloads](https://www.python.org/downloads/).
    *   **Configuración del PATH:** Durante la instalación, marque la casilla "Add Python X.Y to PATH". Si ya está instalado, verifique que las siguientes rutas (o las equivalentes en su sistema) estén en el PATH:
        *   `C:\Users\andre\AppData\Local\Programs\Python\Python313` (ruta a Python)
        *   `C:\Users\andre\AppData\Local\Programs\Python\Python313\Scripts` (ruta a pip)

*   **MySQL (Servidor de Base de Datos):**
    *   Necesita una instancia de MySQL corriendo (puede ser XAMPP, Laragon, WAMP, MAMP, o una instalación directa de MySQL Community Server).
    *   Se requiere una herramienta para gestionar la base de datos (MySQL Workbench, HeidiSQL, DBeaver, phpMyAdmin, etc.).

*   **Git:**
    *   Necesario para clonar el repositorio. Descargar desde: [Git SCM](https://git-scm.com/downloads).

### 2. Configuración del Proyecto Laravel

1.  **Clonar el Repositorio:**
    Abra su terminal y clone el proyecto desde GitHub:
    ```bash
    git clone https://github.com/Andres-OM/PriceBot.git
    ```
    Navegue a la carpeta del proyecto:
    ```bash
    cd PriceBot
    ```

2.  **Instalar Dependencias PHP:**
    ```bash
    composer install
    ```

3.  **Configurar el Archivo de Entorno:**
    Copie el archivo de ejemplo y configúrelo:
    ```bash
    copy .env.example .env
    ```
    Edite el archivo `.env` con sus credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, etc.), `APP_NAME`, y otras configuraciones necesarias.

4.  **Generar Clave de Aplicación:**
    ```bash
    php artisan key:generate
    ```

5.  **Configurar la Base de Datos:**
    *   **Crear la Base de Datos:** Usando su herramienta de gestión de MySQL, cree una nueva base de datos vacía (ej. `pricebot`, o el nombre que especificó en `DB_DATABASE` en su `.env`).
        ```sql
        CREATE DATABASE IF NOT EXISTS pricebot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        ```
    *   **(Opcional) Importar Script SQL Inicial:** Si se proporciona un archivo `PriceBot.sql` (o `datosEjemplo.sql` como mencionaste) que crea tablas iniciales o datos específicos (como las tablas simuladas de WordPress `wpfrk_posts`, `wpfrk_postmeta`), impórtelo en su base de datos `pricebot` usando su herramienta de gestión de MySQL.
        *Nota: Si este script ya crea las tablas, podría entrar en conflicto con las migraciones de Laravel si intentan crear las mismas tablas. Es preferible que las migraciones de Laravel manejen toda la creación de estructura.*

6.  **Ejecutar Migraciones y Seeders de Laravel:**
    Esto creará la estructura de tablas definida en las migraciones de Laravel y poblará la base de datos con datos iniciales de los seeders.
    ```bash
    php artisan migrate
    ```
    O, si quiere asegurarse de que la base de datos esté limpia y luego aplicar todo:
    ```bash
    php artisan migrate:fresh --seed
    ```
    *El comando `--seed` ejecutará los seeders definidos en `DatabaseSeeder.php`.*

7.  **Instalar Dependencias JavaScript y Compilar Assets:**
    ```bash
    npm install
    npm run dev
    ```
    (Deje `npm run dev` corriendo en una terminal si usa Vite con HMR, y abra una nueva para el siguiente paso).

### 3. Configuración y Ejecución del Script de Scraping (Python)

1.  **Navegar a la Carpeta del Script:**
    Desde la raíz del proyecto, navegue a la carpeta del script de scraping:
    ```bash
    cd scraping_project
    ```

2.  **(Recomendado) Crear y Activar un Entorno Virtual Python:**
    ```bash
    python -m venv venv
    # Windows CMD:
    venv\Scripts\activate
    # Windows PowerShell:
    # .\venv\Scripts\Activate.ps1
    # Linux/macOS:
    # source venv/bin/activate
    ```

3.  **Instalar Dependencias de Python:**
    Con el entorno virtual activado (o globalmente si prefiere, aunque no es recomendado):
    ```bash
    pip install selenium mysql-connector-python webdriver-manager
    ```
    *(He omitido `woocommerce` y `sshtunnel` ya que estaban comentados en tu script).*

4.  **Configurar ChromeDriver:**
    *   El script está configurado para usar `webdriver-manager` (recomendado), que gestionará ChromeDriver automáticamente.
    *   Alternativamente, puede descargar `chromedriver.exe` compatible con su versión de Chrome y colocarlo en la subcarpeta `chromedriver-win64` o asegurarse de que esté en su PATH, y ajustar el script si no usa `webdriver-manager`.

5.  **Ajustar Conexión a Base de Datos en `scraping.py`:**
    Verifique que la conexión a la base de datos dentro del archivo `scraping.py` apunte a su base de datos local configurada en el paso 2.5 (ej. host `127.0.0.1`, usuario `root`, su contraseña, database `pricebot`).

6.  **Ejecutar el Script de Scraping:**
    *   Asegúrese de que VS Code (o el editor que use) confíe en la carpeta del proyecto para evitar el "Restricted Mode".
    *   Ejecute el script:
        ```bash
        python scraping.py
        ```
    Esto recopilará los precios y los guardará en su base de datos.

### 4. Iniciar la Aplicación Web PriceBot

1.  **Asegúrese de que `npm run dev` esté corriendo** (en una terminal separada, si es necesario).
2.  En otra terminal, desde la raíz del proyecto `PriceBot`, inicie el servidor de Laravel:
    ```bash
    php artisan serve
    ```
3.  Abra su navegador y vaya a la dirección proporcionada (normalmente `http://localhost:8000` o `http://127.0.0.1:8000`).

**Credenciales de Acceso de Ejemplo:**

*   **Usuario:** `admin@example.com` (Cuidado con el posible error tipográfico, ¿quizás `admin@example.com`?)
*   **Contraseña:** `password`

---

**Notas sobre la adaptación:**

*   He intentado mantener la estructura de tu información pero dándole un formato más estándar de README.
*   **PHP.ini:** La instrucción de "Cambiar php.ini-development por mi php.ini que se encuentra en el proyecto" es un poco inusual. Normalmente, se configura el `php.ini` de la instalación de PHP del sistema, no se reemplaza con uno del proyecto (a menos que sea para un servidor web específico como Apache que pueda cargar un php.ini por directorio, pero para CLI y el servidor de desarrollo de Laravel se usa el global). Lo he redactado para que se entienda que hay que *configurar* el php.ini, y que si provees uno, es una referencia.
*   **Script SQL vs Migraciones:** He puesto una nota sobre la posible redundancia o conflicto si `PriceBot.sql` crea tablas que luego las migraciones de Laravel también intentan crear. Es mejor dejar que las migraciones de Laravel manejen toda la creación de la estructura de la aplicación. Si `PriceBot.sql` solo contiene los `INSERT` para las tablas `wpfrk_posts` y `wpfrk_postmeta` (después de que las migraciones las hayan creado), entonces está bien.
*   **Credenciales:** Corregí el email de ejemplo a `admin@example.com` que es más estándar.
*   **WebDriver-Manager:** He añadido la instalación de `webdriver-manager` ya que es la forma más sencilla de manejar ChromeDriver.
