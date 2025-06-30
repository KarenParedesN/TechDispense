
import network
import time
import urequests 
import ujson 
from machine import Pin


WIFI_SSID = "CHKO"
WIFI_PASS = "AleCheko06_30Hernandez11"
FIREBASE_URL = "https://dispensadoriteligente-default-rtdb.firebaseio.com" 
DISPENSER_ID = "dispenser_001"
LED_PIN = 25  


led = Pin(LED_PIN, Pin.OUT)
led.off() 


def connect_wifi():
    sta_if = network.WLAN(network.STA_IF)
    if not sta_if.isconnected():
        print('Conectando a WiFi...')
        led.on() 
        sta_if.active(True)
        sta_if.connect(WIFI_SSID, WIFI_PASS)
        while not sta_if.isconnected():
            time.sleep(0.5)
        led.off() 
    print('Conexión WiFi establecida:', sta_if.ifconfig())
    return True

def blink_led(times=1, duration=10 ):
    for _ in range(times):
        led.on()
        time.sleep(duration)
        led.off()
        time.sleep(duration)

def dispense_action():
    print("Acción de dispensar (simulada con LED)...")
    blink_led(times=1, duration=10) 
    print("Producto 'dispensado'.")

def update_firebase_stock(current_stock):
    new_stock = current_stock -1
    if new_stock < 0: new_stock = 0
    try:
        stock_path = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/status/stock_level.json"
        response = urequests.put(stock_path, data=ujson.dumps(new_stock))
        print(f"Stock actualizado en Firebase a {new_stock}. Respuesta: {response.status_code}")
        response.close()
        if new_stock < 10: 
            print("ALERTA: Nivel de stock bajo!")
           
    except Exception as e:
        print(f"Error actualizando stock en Firebase: {e}")
    return new_stock

def check_for_dispense_requests():
    print("Verificando solicitudes de dispensación...")
   
    query_params = 'orderBy="status"&equalTo="pending"' 
    request_url = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/dispense_requests.json?{query_params}"

    try:
        response = urequests.get(request_url)
        if response.status_code == 200:
            requests = response.json()
            if requests: 
                print(f"Solicitudes pendientes encontradas: {len(requests)}")
             
                request_id = list(requests.keys())[0] 
                request_data = requests[request_id]

                print(f"Procesando solicitud ID: {request_id}")

                log_check_url = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/dispensed_log/{request_id}.json"
                log_response = urequests.get(log_check_url)
                already_dispensed = log_response.json() is not None
                log_response.close()

                if not already_dispensed:
                    dispense_action() 

                    # Marcar la solicitud como "completed"
                    update_status_url = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/dispense_requests/{request_id}/status.json"
                    status_put_response = urequests.put(update_status_url, data=ujson.dumps("completed"))
                    print(f"Estado de solicitud {request_id} actualizado a 'completed'. Respuesta: {status_put_response.status_code}")
                    status_put_response.close()

                    # Añadir al log de dispensados
                    add_to_log_url = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/dispensed_log/{request_id}.json"
                    log_put_response = urequests.put(add_to_log_url, data=ujson.dumps(True)) # Guardar true o un timestamp
                    print(f"Solicitud {request_id} añadida al log de dispensados. Respuesta: {log_put_response.status_code}")
                    log_put_response.close()

                    # Actualizar stock 
                    stock_read_url = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/status/stock_level.json"
                    stock_res = urequests.get(stock_read_url)
                    current_stock = 100 
                    if stock_res.status_code == 200 and stock_res.json() is not None:
                        current_stock = int(stock_res.json())
                    stock_res.close()
                    update_firebase_stock(current_stock)

                else:
                    print(f"Solicitud {request_id} ya fue dispensada previamente. Marcando como 'duplicate'.")
                   
                    update_status_url = f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/dispense_requests/{request_id}/status.json"
                    status_put_response = urequests.put(update_status_url, data=ujson.dumps("duplicate_check"))
                    status_put_response.close()

            else:
                print("No hay solicitudes pendientes.")
        else:
            print(f"Error al leer solicitudes de Firebase: {response.status_code} - {response.text}")
        response.close()
    except Exception as e:
        print(f"Excepción al verificar solicitudes: {e}")
        # Reconectar WiFi aquí si es un error de red
        # connect_wifi() 

def send_heartbeat():
    print("Enviando heartbeat y estado a Firebase...")
    status_data = {
        "is_online": True,
        "last_seen": time.time(), # Timestamp UNIX
  
    }
    try:
        # Usamos PATCH para actualizar solo estos campos sin borrar otros en 'status'
        response = urequests.patch(f"{FIREBASE_URL}/dispensers/{DISPENSER_ID}/status.json", data=ujson.dumps(status_data))
        print(f"Heartbeat enviado. Respuesta: {response.status_code}")
        response.close()
        blink_led(1, 0.1) 
    except Exception as e:
        print(f"Error enviando heartbeat: {e}")

# --- Bucle Principal ---
if not connect_wifi():
    print("No se pudo conectar a WiFi. Reiniciando en 30 segundos...")
    time.sleep(30)
    # machine.reset() # reiniciar si no puede conectar

send_heartbeat() # Enviar estado inicial

last_request_check = time.time()
last_heartbeat = time.time()

while True:
    current_time = time.time()

    # Verificar solicitudes cada 10 segundos
    if current_time - last_request_check > 10:
        if not network.WLAN(network.STA_IF).isconnected():
            print("WiFi desconectado. Intentando reconectar...")
            connect_wifi() 

        if network.WLAN(network.STA_IF).isconnected():
            check_for_dispense_requests()
        last_request_check = current_time

    
    if current_time - last_heartbeat > 60:
        if network.WLAN(network.STA_IF).isconnected(): 
            send_heartbeat()
        last_heartbeat = current_time

    time.sleep(1) 