import machine
import network
import time
from umqtt.simple import MQTTClient

# --- Configuración Wi-Fi ---
WIFI_SSID = ""  
WIFI_PASSWORD = "" 

# --- Configuración MQTT ---
MQTT_BROKER = b"test.mosquitto.org" 
# MQTT_CLIENT_ID = b"esp32-survey-responder-" + machine.unique_id() 
MQTT_CLIENT_ID = b"esp32-depuracion-cliente"
MQTT_PORT = 1883 
MQTT_TOPIC_SURVEY_COMPLETED = b"bytelink/IoT/encuesta/completada" 

# --- Configuración del Servomotor y LED ---
SERVO_PIN = 14 
LED_PIN = 25 

# Valores de ancho de pulso para el servomotor (ajusta estos para tu servo específico)
SERVO_MIN_DUTY = 40  # Posición inicial (aprox. 0 grados)
SERVO_MAX_DUTY = 90 # Posición final (aprox. 90 grados)

# Inicializar PWM para el servomotor y pin para el LED
servo_pwm = machine.PWM(machine.Pin(SERVO_PIN), freq=50) 
led_gpio = machine.Pin(LED_PIN, machine.Pin.OUT) 

# --- Conexión Wi-Fi ---
def connect_wifi():
    station = network.WLAN(network.STA_IF)
    station.active(True)
    if not station.isconnected():
        print('Conectando a la red Wi-Fi...')
        station.connect(WIFI_SSID, WIFI_PASSWORD)
        while not station.isconnected():
            time.sleep(1)
            print('.')
    print('Conexion Wi-Fi exitosa!')
    print('Configuracion IP:', station.ifconfig())

# --- Callback de mensajes MQTT ---
def mqtt_callback(topic, msg):
    print(f"Mensaje MQTT recibido en topico '{topic.decode()}': '{msg.decode()}'")
    if topic == MQTT_TOPIC_SURVEY_COMPLETED:
        command = msg.decode()
        if command == "encuesta_completada":
            print("Encuesta completada, activando servo y LED brevemente.")
            
            # Activar LED
            led_gpio.value(1)
            
            # Activar servomotor
            servo_pwm.duty(SERVO_MAX_DUTY) 
            
            time.sleep(0.15) 
            
            # Desactivar LED
            led_gpio.value(0) 
            
            # Desactivar servomotor
            servo_pwm.duty(SERVO_MIN_DUTY) 
        else:
            print(f"Comando de encuesta desconocido: {command}")

# --- Función para conectar a MQTT ---
def connect_mqtt():
    global client
    try:
        print(f"Intentando conectar a MQTT. Cliente ID: {MQTT_CLIENT_ID.decode()}, Broker: {MQTT_BROKER.decode()}, Puerto: {MQTT_PORT}")
        client = MQTTClient(MQTT_CLIENT_ID, MQTT_BROKER, port=MQTT_PORT)
        client.set_callback(mqtt_callback)
        client.connect() 
        print(f"Conectado al broker MQTT: {MQTT_BROKER.decode()}") 
        # Suscribirse al tópico de encuestas completadas
        client.subscribe(MQTT_TOPIC_SURVEY_COMPLETED)
        print(f"Suscrito a topico: {MQTT_TOPIC_SURVEY_COMPLETED.decode()}")
        return client
    except Exception as e:
        print(f"Error al conectar a MQTT: {e}")
        return None

# --- Bucle principal ---
def main():
    connect_wifi()
    client = None
    while client is None:
        client = connect_mqtt()
        if client is None:
            time.sleep(5) 

    # Asegurarse de que el servo y el LED estén en la posición inicial al iniciar
    servo_pwm.duty(SERVO_MIN_DUTY)
    led_gpio.value(0) 

    while True:
        try:
            client.check_msg() 
        except OSError as e:
            print(f"Error de red, reconectando a MQTT: {e}")
            client = None
            while client is None:
                client = connect_mqtt()
                if client is None:
                    time.sleep(5)
        time.sleep(0.1) 

if __name__ == "__main__":
    main()