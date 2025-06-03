# PriceBot - Aplicación de Comparación de Precios

PriceBot es una aplicación web que permite recopilar los precios de productos de tiendas online y compararlos con los de la competencia para ayudar a ajustar los precios de una tienda propia y ser competitivo en el mercado.

### 1. Requisitos Previos

Asegúrese de tener instalados los siguientes componentes en su sistema:

*   **PHP (versión 8.1.0):**
    *   Descargar desde: [PHP Archives](https://windows.php.net/downloads/releases/archives/) (versión 8.1.0).
    *   **Configuración del PATH:** Añadir la ruta a la carpeta de PHP (ej. `C:\php-8.1.0`) a la variable de entorno PATH de su sistema.
    *   **Archivo `php.ini`:**
        1.  En su carpeta de instalación de PHP, elimine o renombre el archivo `php.ini-development`.
        2.  Copie el archivo `php.ini` que se encuentra en la raíz de este proyecto a su carpeta de instalación de PHP. Este archivo ya tiene las extensiones necesarias para Laravel habilitadas.

*   **Node.js (versión LTS):**
    *   Descargar desde: [Node.js Oficial](https://nodejs.org/es).
    *   Durante la instalación, asegúrese de que Node.js y npm se añadan al PATH. `npm` se instala automáticamente con Node.js.

*   **Python (versión 3.x):**
    *   Descargar desde: [Python.org Downloads](https://www.python.org/downloads/).
    *   **Configuración del PATH:** Durante la instalación, marque la casilla "Add Python X.Y to PATH". Si ya está instalado, verifique que las siguientes rutas (o las equivalentes en su sistema) estén en el PATH:
        *   `C:\Users\andre\AppData\Local\Programs\Python\Python313` (ruta a Python)
        *   `C:\Users\andre\AppData\Local\Programs\Python\Python313\Scripts` (ruta a pip)

*   **MySQL (Servidor de Base de Datos):**
    *   Es necesario MySQL Workbench: [mysql] (https://dev.mysql.com/downloads/workbench/).

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
    ```bash
    npm install
    ```

3.  **Configurar la Base de Datos:**

    *   Edita el archivo .env para que corresponda con sus credenciales de base de datos (B_DATABASE=pricebot, DB_USERNAME, DB_PASSWORD, etc.)

4.  **Configurar la Base de Datos:**
    *   Ejecute el script `PriceBot.sql` (incluido en el proyecto) usando su herramienta de gestión de MySQL. Este script creará la base de datos `pricebot` (si no existe) y las tablas iniciales necesarias, incluyendo datos de ejemplo para las tablas `wpfrk_posts` y `wpfrk_postmeta`.

5.  **Ejecutar Migraciones y Seeders de Laravel:**
    Esto aplicará cualquier migración adicional de Laravel y poblará las tablas con datos de los seeders (como tiendas y el usuario administrador).
    ```bash
    php artisan migrate
    php artisan db:seed
    ```
    *Nota: Si el script `PriceBot.sql` ya creó todas las tablas, `php artisan migrate` podría indicar "Nothing to migrate". Si desea una recreación completa basada en las migraciones de Laravel, asegúrese de que la base de datos esté vacía de tablas de la aplicación antes de `migrate` y use `php artisan migrate:fresh --seed`.*

6.  **Insertar datos de ejemplo**
    Ejecuta el script `datosEjemplo.sql` para añadir los precios de prueba.


### 3. Configuración y Ejecución del Script de Scraping (Python)

1.  **Navegar a la Carpeta del Script:**
    Desde la raíz del proyecto, navegue a la carpeta del script de scraping:
    ```bash
    cd scraping_project
    ```

2.  **Instalar Dependencias de Python (Globalmente):**
    ```bash
    pip install selenium mysql-connector-python webdriver-manager
    ```

4.  **Ajustar Conexión a Base de Datos en `scraping.py`:**
    Verifique que la conexión a la base de datos dentro del archivo `scraping.py` apunte a su base de datos local `pricebot` configurada en el paso 2.3.

5.  **Ejecutar el Script de Scraping:**
    *   Asegúrese de que VS Code (o el editor que use) confíe en la carpeta del proyecto para evitar el "Restricted Mode". Ejecutelo por comando o desde Visual Studio u otro entorno de desarrollo
    *   Ejecute el script:
        ```bash
        python scraping.py
        ```
    Esto recopilará los precios y los guardará en su base de datos.

### 4. Iniciar la Aplicación Web PriceBot

1.  En una terminal, desde la raíz del proyecto `PriceBot`, inicie el servidor de Laravel:
    ```bash
    php artisan serve
    ```
3.  Abra su navegador y vaya a la dirección proporcionada (normalmente `http://localhost:8000` o `http://127.0.0.1:8000`).

**Credenciales de Acceso:**

*   **Usuario:** `admin@ejample.es`
*   **Contraseña:** `password`