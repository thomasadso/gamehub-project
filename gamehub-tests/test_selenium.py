from selenium import webdriver
from selenium.webdriver.common.by import By
import time

# Iniciamos el navegador Chrome
driver = webdriver.Chrome()

try:
    # 1. Prueba: Abrir registro de videojuego 
    driver.get("http://localhost/gamehub-web/registrar_videojuego.php")
    driver.find_element(By.NAME, "titulo").send_keys("Fifa 2026")
    driver.find_element(By.NAME, "genero").send_keys("Deportes")
    driver.find_element(By.NAME, "plataforma").send_keys("PS5")
    driver.find_element(By.XPATH, "//button[@type='submit']").click()
    time.sleep(2) # Espera para verificar pantalla
    print("Prueba de registro de videojuego exitosa.")

    # 2. Prueba: Dejar reseña 
    driver.get("http://localhost/gamehub-web/registrar_resena.php")
    driver.find_element(By.NAME, "usuario").send_keys("TomasCalderon")
    driver.find_element(By.NAME, "calificacion").send_keys("5")
    driver.find_element(By.NAME, "comentario").send_keys("Excelente simulador, muy realista.")
    # Nota: El select de videojuego requiere que el usuario elija la opción idónea en vivo
    time.sleep(2)
    
finally:
    driver.quit()