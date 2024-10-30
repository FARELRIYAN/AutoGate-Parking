# AutoGate-Parking

## Project Overview
**AutoGate-Parking** is an automated parking gate system using RFID technology and ESP32. The system reads RFID cards/tags to control access, and it operates a barrier gate using a servo motor. Additionally, it uses a buzzer and LED for audio and visual notifications, providing an efficient and secure way to manage parking access.

## Components
The following components are used in the **AutoGate-Parking** project:

- **ESP32** - Main microcontroller to control the system.
- **RFID Reader MFRC522** - Reads RFID cards/tags for access control.
- **RFID Cards/Tags** - Used as access cards for parking.
- **Servo Motor** - Controls the movement of the gate barrier.
- **Buzzer** - Provides audio notifications.
- **LED** - Provides visual notifications.
- **Breadboard and Jumper Wires** - Used for component connections.
- **Power Supply** - Powers the ESP32 and other components.

## Component Wiring
The following connections should be made between each component and the ESP32.

### 1. RFID Reader MFRC522 to ESP32
- **VCC** on MFRC522 to **3.3V** on ESP32
- **GND** on MFRC522 to **GND** on ESP32
- **SDA** on MFRC522 to **GPIO 5** on ESP32
- **SCK** on MFRC522 to **GPIO 18** on ESP32
- **MOSI** on MFRC522 to **GPIO 23** on ESP32
- **MISO** on MFRC522 to **GPIO 19** on ESP32
- **RST** on MFRC522 to **GPIO 22** on ESP32

### 2. Servo Motor to ESP32
- **VCC** on Servo to **5V** on ESP32
- **GND** on Servo to **GND** on ESP32
- **Signal** on Servo to **GPIO 13** on ESP32

### 3. Buzzer to ESP32
- **Positive (+)** on Buzzer to **GPIO 15** on ESP32
- **Negative (-)** on Buzzer to **GND** on ESP32

### 4. LED to ESP32
- **Anode (+)** on LED to **GPIO 14** on ESP32 (use a 220-ohm resistor if necessary)
- **Cathode (-)** on LED to **GND** on ESP32

### 5. Ultrasonic Sensor HC-SR04 to ESP32
- **VCC** on Ultrasonic Sensor to **5V** on ESP32
- **GND** on Ultrasonic Sensor to **GND** on ESP32
- **TRIG** on Ultrasonic Sensor to GPIO (e.g., **GPIO 4** on ESP32)
- **ECHO** on Ultrasonic Sensor to GPIO (e.g., **GPIO 2** on ESP32)

---

Ensure that all components are connected correctly according to this wiring guide to maintain system reliability and functionality. With proper configuration, the **AutoGate-Parking** system will function as an efficient, automated parking gate, providing a seamless experience for users.
