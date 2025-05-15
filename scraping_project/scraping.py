from selenium import webdriver
import time
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from woocommerce import API
from selenium.common.exceptions import NoSuchElementException, TimeoutException
from sshtunnel import SSHTunnelForwarder
import mysql.connector
import os
import mysql.connector

#____________________________________________________________
def normalizar_sku(sku):
    return sku.zfill(13)
#________________________________________________________________________________________________________________________________________________________________
def obtener_precio_tienda(driver,url, nombre_precio, sku):
    print(url)
    try:
        if url.startswith("https://www.elcorteingles"):
            sku = normalizar_sku(sku)
            driver.get(url+sku) 
            #Si es el corte inglés busca tanto la parte entera como decimal
            parte_entera = WebDriverWait(driver, 6).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio))).text
            parte_decimal = WebDriverWait(driver, 0).until(EC.presence_of_element_located((By.CLASS_NAME, 'price._big'))).find_elements(By.TAG_NAME, 'span')[1].text
            precio = f'{parte_entera}.{parte_decimal}'
        elif url.startswith("https://www.amazon"):
            sku = normalizar_sku(sku)
            driver.get(url+sku) 
            #Si el título tiene el mensaje de error de AMAZON devuelve no encontrado, pero si no realiza la búsqueda
            mensaje = WebDriverWait(driver, 0).until(EC.presence_of_element_located((By.TAG_NAME, 'h1'))).text
            if mensaje == 'Utilice menos palabras clave o pruebe con estas':
                precio = "No encontrado"
            else:
                #precio_elemento = WebDriverWait(driver, 0).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio)))
                contenedor_producto = WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.CSS_SELECTOR, 'div[data-index="2"]')))
                precio_elemento = contenedor_producto.find_element(By.CLASS_NAME, nombre_precio)

                precio = precio_elemento.text.replace('\n', '.').strip()
                precio = precio.replace(',', '.')
                precio = precio.replace("€", "") 
        elif url.startswith("https://www.carrefour"):
            sku = normalizar_sku(sku)
            driver.get(url+sku) 
            #Comprobación de CARREFOUR cuando no ha encontrado el resultado devuelva el mensaje de error y no producto recomendado
            mensaje = WebDriverWait(driver, 8).until(EC.presence_of_element_located((By.CLASS_NAME, "ebx-results-number"))).text
            if mensaje == "Mostrando 1 resultados":
                precio_elemento = WebDriverWait(driver, 2).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio)))
                precio = precio_elemento.text.replace('\n', '.').strip()
                precio = precio.replace(',', '.')
                precio = precio.replace("€", "")
            else:
                precio = "No encontrado" 
        elif url.startswith("https://www.dynos"):
            #Comprobación de DYNOS quitando el 0 que sobra y obteniendo de forma personalizada el precio
            driver.get(url+sku) 
            precio_elemento = WebDriverWait(driver, 0).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio))).text
            precio = precio_elemento.replace(',', '.')
            precio = precio.replace("€", "")
        elif url.startswith("https://comicstores"):
            driver.get(url + sku)
            precio_elemento = WebDriverWait(driver, 2).until(EC.presence_of_element_located((By.CSS_SELECTOR, "p.precio strong"))).text
            print(precio_elemento)
            precio = precio_elemento.replace(',', '.').replace('€', '').strip()
        elif url.startswith("https://www.game"):
            sku = normalizar_sku(sku)
            driver.get(url + sku)
            parte_entera = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CLASS_NAME, "int"))).text.strip()
            parte_decimal = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CLASS_NAME, "decimal"))).text.strip().replace("'", "") 
            precio = f"{parte_entera}.{parte_decimal}"         
        else:
            driver.get(url+sku) 
            precio_elemento = WebDriverWait(driver, 3).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio)))
            precio = precio_elemento.text.replace('\n', '.').strip()
            precio = precio.replace(',', '.')
            precio = precio.replace("€", "")
    except NoSuchElementException:
        precio = "No encontrado"
    except TimeoutException:
        precio = "No encontrado"

    return precio

#________________________________________________________________________________________________________________________________________________________________
#Conexión mediante túnel ssh
"""
# Configuración del túnel SSH
ssh_host = '188.165.131.254'  # Dirección IP del servidor SSH
ssh_username = 'todofriki'  # Nombre de usuario para la conexión SSH
ssh_password = 'hGAixEOsHrpTZEP'  # Contraseña SSH (es más seguro usar una clave privada)
#ssh_private_key = "/path/to/private/key"  # Ruta al archivo de clave privada, si usas una

# Configuración de la base de datos
db_host = 'localhost'  # Dirección del host de la base de datos, localhost si es local respecto al servidor SSH
db_port = 3306         # Puerto de la base de datos
db_user = 'todofriki'  # Usuario de la base de datos
db_password = 'rjEFgqqu8tOXJpv' # Contraseña de la base de datos
db_name = 'todofriki'  # Nombre de la base de datos

# Crear el túnel SSH
with SSHTunnelForwarder(
    (ssh_host, 22),
    ssh_username=ssh_username,
    ssh_password=ssh_password,  # Comenta esta línea si usas una clave privada
    # ssh_pkey=ssh_private_key, # Descomenta y usa esta línea si prefieres usar clave privada
    remote_bind_address=(db_host, db_port)
) as tunnel:
    print('___________________T U N E L______________________')
    # Conexión a la base de datos a través del túnel
    connection = mysql.connector.connect(
        host='127.0.0.1',  # Conectarse a localhost del túnel SSH
        port=tunnel.local_bind_port,  # Usar el puerto asignado localmente por el túnel
        user=db_user,
        password=db_password,
        database=db_name
    )
cursor = connection.cursor()
"""
#________________________________________________________________________________________________________________________________________________________________
#Conexión a la BBDD y obtención de datos tienda 

connection = mysql.connector.connect(
    host="192.168.56.56",
    user="homestead",
    password="secret",
    database="todofriki"
)

#Obtención de tiendas

cursor = connection.cursor()
cursor.execute("SELECT * FROM tiendas")
tiendas  = cursor.fetchall()

tiendas_data = []
for row in tiendas:
    tienda_dict = {
        "id": row[0],
        "nombre": row[1],
        "URL": row[2],
        "nombrePrecio": row[3]
    }
    tiendas_data.append(tienda_dict)

#ver contenido de la tabla tiendas
"""
for row in tiendas:
    print(row)
"""
#_______________________________________________________________________________________________________________________________________________________
#Obtención de productos
query = """
SELECT 
    m.meta_value AS sku, 
    p.post_title AS nombre,
    pm.meta_value AS precio
FROM wpfrk_posts p
JOIN wpfrk_postmeta m ON p.ID = m.post_id AND m.meta_key = '_sku'
JOIN wpfrk_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_price'
JOIN wpfrk_postmeta ps ON p.ID = ps.post_id AND ps.meta_key = '_stock_status'
WHERE p.post_type = 'product' AND ps.meta_value = 'instock';
"""
cursor.execute(query)
productos = cursor.fetchall()

# Guardar datos en all_products
all_products = []

for row in productos:
    sku, nombre, precio = row
    all_products.append({
        "sku": sku,
        "nombre": nombre,
        "precio": precio
    })

# Imprimir detalles de los productos
"""
for producto in all_products:
    print(f"Producto: {producto['nombre']}, SKU: {producto['sku']}, Precio: {producto['precio']}")
print(f"Cantidad total de productos en stock: {len(all_products)}")
"""

#_____________________________________________________________________________________________________________________________________________________________________
# API para obtener datos de la web
wcapi = API(
    url="https://www.todofriki.com/",
    consumer_key="ck_3bf24cf1d674461c64f1b0c26e5e0e105f55286a",
    consumer_secret="cs_2add26f1d0c52e8c8afa0c1488660766311959df",
    version="wc/v3"
)

#RECORRER PRODUCTOS
#Todo
"""
all_products = []
page_number = 1
products_per_page = 100

while True:
    products = wcapi.get("products", params={"page": page_number, "per_page": products_per_page}).json()
    if not products:
        break

    all_products.extend(products)
    page_number += 1

print("Cantidad total de productos obtenidos:", len(all_products))
"""

#Filtrado (ELEGIR DE PREDETERMINADO)
"""
all_products = []
unique_ids = set()
page_number = 1
products_per_page = 100

while True:
    products = wcapi.get("products", params={"page": page_number, "per_page": products_per_page}).json()
    if not products:
        break

    for product in products:
        product_id = product["id"]
        #filtra para que introduzca los productos con ID único una sola vez y que tenga stock, por lo tanto aparezca en la web
        if product_id not in unique_ids and product.get("stock_status") == "instock":
            unique_ids.add(product_id)
            all_products.append(product)

    page_number += 1

print("Cantidad total de productos obtenidos:", len(all_products))
"""
#Mostrar 100 productos de la API CON FILTROS
"""
all_products = []
unique_ids = set()
page_number = 1
products_per_page = 100
products = wcapi.get("products", params={"page": page_number, "per_page": products_per_page}).json()
for product in products:
    product_id = product["id"]
    if product_id not in unique_ids and product.get("stock_status") == "instock":
        unique_ids.add(product_id)
        all_products.append(product)
print("Cantidad total de productos obtenidos:", len(all_products))
"""

#Ver todos los productos
"""
for product in all_products:
    #listaprecios = obtener_precio_tienda((tiendas[3], tiendas[4], tiendas[5], product["sku"]))
    #print(f'El precio del producto "{ean}" en Amazon es: {precio_amazon}')
        print("Cantidad total de productos obtenidos:", len(all_products))
        print("Nombre del producto:", product["name"])
        print("Precio del producto:", product["price"])
        print("EAN del producto:", product["sku"])
        #Mostrar todos los campos
        for key, value in product.items():
            print(f"{key}: {value}")
        print("-------------------------------------")
"""

#_____________________________________________________________________________________________________________________________________________________________________
#Busqueda de precios

for product in all_products:
    try:
        print(f"(SKU: {product['sku']}, Producto: {product['nombre']})")
        for tienda in tiendas_data:
            nombre_tienda = tienda["nombre"]
            url_tienda = tienda["URL"]
            nombre_precio = tienda["nombrePrecio"]
            numero_ean= product['sku']
            nombre_producto = product['nombre']
            #_____________________________________________________________________________________________________________________________________________________________________
            #Configuración de navegador
            options = webdriver.ChromeOptions()
            options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3")
            driver = webdriver.Chrome(options=options)
            precio_producto = obtener_precio_tienda(driver,url_tienda, nombre_precio, numero_ean)
            driver.quit
            if precio_producto != "No encontrado":
                cursor.execute(
                    "INSERT INTO precios (ean, nombre, tienda, precio, fecha_creacion) "
                    "VALUES (%s, %s, %s, %s, CURDATE()) "
                    "ON DUPLICATE KEY UPDATE precio = VALUES(precio), nombre = VALUES(nombre)",
                    (numero_ean, nombre_producto, nombre_tienda, precio_producto)
                )
                cursor.execute(
                "INSERT INTO precios (ean, nombre, tienda, precio, fecha_creacion) "
                "VALUES (%s, %s, %s, %s, CURDATE()) "
                "ON DUPLICATE KEY UPDATE precio = VALUES(precio), nombre = VALUES(nombre)",
                (numero_ean, nombre_producto, 'Todofriki', product["precio"]))
                connection.commit()
                
            print(f"Precio en {nombre_tienda}: {precio_producto}")
        print("============================================================")
    except Exception as e:
        print(f"Error al obtener el precio del producto: {e}")
        continue 

#Prueba individual
"""
options = webdriver.ChromeOptions()
options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3")
driver = webdriver.Chrome(options=options)
url_tienda = 'https://www.amazon.es/s?k='
nombre_precio = 'a-price'
sku_producto = '5702017419640'
precio_funcionGeneral = obtener_precio_tienda(driver,url_tienda, nombre_precio, sku_producto)
print(f'El precio del producto "{sku_producto}" en la tienda es: {precio_funcionGeneral}')
"""

#Cerrar navegador,cursor y conexión mysql
driver.quit()
cursor.close()
connection.close()