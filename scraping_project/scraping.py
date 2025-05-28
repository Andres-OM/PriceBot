from selenium import webdriver
import time
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import NoSuchElementException, TimeoutException
import mysql.connector
import os


#____________________________________________________________
#Función para asegurar que el sku sea de 13 digitos
def normalizar_sku(sku):
    return sku.zfill(13)
#________________________________________________________________________________________________________________________________________________________________
#Función para obtener el precio dependiendo de la tienda que esté analizando
def obtener_precio_tienda(driver,url, nombre_precio, sku):
    print(url)
    try:
        if url.startswith("https://www.elcorteingles"):
            sku = normalizar_sku(sku)
            driver.get(url+sku) 
            #Busca el precio dentro del elemento pasado por parametro.
            parte_entera = WebDriverWait(driver, 6).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio))).text
            precio = parte_entera.replace('€', '').replace(',', '.').strip()
        elif url.startswith("https://www.amazon"):
            sku = normalizar_sku(sku)
            driver.get(url+sku) 
            #Si el título tiene el mensaje de error de AMAZON devuelve no encontrado, pero si no realiza la búsqueda
            mensaje = WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.TAG_NAME, 'h1'))).text
            if mensaje == 'Utilice menos palabras clave o pruebe con estas':
                precio = "No encontrado"
            else:
                #precio_elemento = WebDriverWait(driver, 0).until(EC.presence_of_element_located((By.CLASS_NAME, nombre_precio)))
                contenedor_producto = WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.CSS_SELECTOR, 'div[data-index="2"]')))
                precio_elemento = contenedor_producto.find_element(By.CLASS_NAME, nombre_precio)

                precio = precio_elemento.text.replace('\n', '.').strip()
                precio = precio.replace(',', '.')
                precio = precio.replace("€", "") 
        elif url.startswith("https://comicstores"):
            driver.get(url + sku)
            precio_elemento = WebDriverWait(driver, 2).until(EC.presence_of_element_located((By.CSS_SELECTOR, "p.precio strong"))).text
            print(precio_elemento)
            precio = precio_elemento.replace(',', '.').replace('€', '').strip()
        elif url.startswith("https://www.toysrus.es"):
            sku_busqueda = normalizar_sku(sku)
            driver.get(url + sku_busqueda)
            precio_elemento = WebDriverWait(driver, 10).until(EC.visibility_of_element_located((By.CLASS_NAME, nombre_precio)))
            precio_texto_crudo = precio_elemento.text 
            precio_limpio = precio_texto_crudo.replace('€', '').replace('\xa0', '').replace(',', '.').strip() 
            precio = precio_limpio
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
#Conexión a la BBDD y obtención de datos tienda 

connection = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="1234",
    database="pricebot"
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

#Navegador
options = webdriver.ChromeOptions()
options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36") # User agent más reciente
options.add_argument("--disable-blink-features=AutomationControlled")
options.add_experimental_option("excludeSwitches", ["enable-automation"])
options.add_experimental_option('useAutomationExtension', False)
driver = webdriver.Chrome(options=options)
driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")
#_____________________________________________________________________________________________________________________________________________________________________
#Busqueda de precios

for product in all_products:
    #Recorre los productos para buscar el sku y ejecutar el navegador con la función para buscar el precio.
    try:
        print(f"(SKU: {product['sku']}, Producto: {product['nombre']})")
        for tienda in tiendas_data:
            nombre_tienda = tienda["nombre"]
            url_tienda = tienda["URL"]
            nombre_precio = tienda["nombrePrecio"]
            numero_ean= product['sku']
            nombre_producto = product['nombre']
            """
            #_____________________________________________________________________________________________________________________________________________________________________
            #Configuración de navegador
            options = webdriver.ChromeOptions()
            options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3")
            driver = webdriver.Chrome(options=options)
            precio_producto = obtener_precio_tienda(driver,url_tienda, nombre_precio, numero_ean)
            driver.quit
            """
            precio_producto = obtener_precio_tienda(driver, url_tienda, nombre_precio, numero_ean)
            #Inserta los datos en la base de datos
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
                (numero_ean, nombre_producto, 'Pricebot', product["precio"]))
                connection.commit()
                
            print(f"Precio en {nombre_tienda}: {precio_producto}")
        print("============================================================")
    except Exception as e:
        print(f"Error al obtener el precio del producto: {e}")
        continue 


#Cerrar navegador,cursor y conexión mysql
driver.quit()
cursor.close()
connection.close()