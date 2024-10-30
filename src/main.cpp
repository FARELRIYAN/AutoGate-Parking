#include <WiFi.h>
#include <HTTPClient.h>
#include <MFRC522.h>
#include <SPI.h>
#include <ESP32Servo.h>

// WiFi Configuration
const char* ssid = "Rynn.";
const char* password = "iyannzzz";

// RFID Configuration
#define SS_PIN 5
#define RST_PIN 22
MFRC522 rfid(SS_PIN, RST_PIN);

// Servo Configuration
Servo myServo;
int servoPin = 13;

// Buzzer Configuration
int buzzerPin = 15;

// Ultrasonic Sensor Configuration
const int trigPin = 4;
const int echoPin = 2;
long duration;
int distance;

// Function Prototypes
void sendUIDToServer(String uid);
void controlBuzzer(int duration_ms);
bool checkForObject();

void setup() {
    Serial.begin(115200);

    // Connect to WiFi
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Connecting to WiFi...");
    }
    Serial.println("Connected to WiFi");

    // Initialize RFID
    SPI.begin();
    rfid.PCD_Init();

    // Initialize Servo
    myServo.attach(servoPin);
    myServo.write(0); // Start with the barrier closed

    // Initialize Buzzer
    pinMode(buzzerPin, OUTPUT);
    digitalWrite(buzzerPin, LOW); // Ensure buzzer is off at startup

    // Initialize Ultrasonic Sensor
    pinMode(trigPin, OUTPUT);
    pinMode(echoPin, INPUT);
}

void loop() {
    // Check for new RFID card
    if (!rfid.PICC_IsNewCardPresent()) {
        // No card detected, keep buzzer off
        digitalWrite(buzzerPin, LOW);
        return;
    }
    if (!rfid.PICC_ReadCardSerial()) return;

    // Read UID from the RFID card
    String uid = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
        uid += String(rfid.uid.uidByte[i], HEX);
    }
    uid.toUpperCase();
    Serial.println("UID: " + uid);

    // Send UID to the server
    sendUIDToServer(uid);

    // Halt reading to avoid multiple scans
    rfid.PICC_HaltA();
    delay(1000);
}

void sendUIDToServer(String uid) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin("http://192.168.199.184/Parking/upload.php"); 
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        String httpRequestData = "uid=" + uid;
        Serial.println("Sending POST request: " + httpRequestData);
        int httpResponseCode = http.POST(httpRequestData);

        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.println("Server response: " + response);

            // Control servo and buzzer based on server response
            if (response == "open") {
                myServo.write(90); // Open barrier
                controlBuzzer(-1);  // Buzzer stays off when access is granted
                
                // Check for object in front of servo
                while (checkForObject()) {
                    Serial.println("Object detected, keeping barrier open.");
                    delay(5000);
                }
                
                myServo.write(0); // Close barrier after object is gone
                controlBuzzer(0);
            } else {
                Serial.println("Access denied");
                controlBuzzer(5000); // Beep for access denied
            }
        } else {
            Serial.print("Error in sending POST request. HTTP Response code: ");
            Serial.println(httpResponseCode);  // Print response code for debugging
        }
        http.end();
    } else {
        Serial.println("WiFi Disconnected");
    }
}

void controlBuzzer(int duration_ms) {
    if (duration_ms == -1) {
        // Keep the buzzer ON continuously
        digitalWrite(buzzerPin, HIGH);  // Buzzer ON
    } else if (duration_ms > 0) {
        // Beep the buzzer for the given duration
        digitalWrite(buzzerPin, HIGH);  // Buzzer ON
        delay(duration_ms);  // Delay for the specified duration
        digitalWrite(buzzerPin, LOW);  // Buzzer OFF
    } else {
        // Turn off the buzzer
        digitalWrite(buzzerPin, LOW);  // Buzzer OFF
    }
}


bool checkForObject() {
    // Send a trigger pulse to the ultrasonic sensor
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);

    // Measure the duration of the echo pulse
    duration = pulseIn(echoPin, HIGH);

    // Calculate the distance in cm
    distance = duration * 0.034 / 2;

    // Print the distance for debugging
    Serial.print("Distance: ");
    Serial.println(distance);

    // Return true if object is detected (distance < 20 cm, for example)
    return (distance < 20);
}
