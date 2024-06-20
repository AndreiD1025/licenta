#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "UPC316FBEF";
const char* password = "cddkuny5CDDKUNY5";
IPAddress server_addr(192,168,0,73);
char user[] = "root";
char pass[] = "";
char db[]="SistemIrigatii";
String URL = "http://192.168.0.73/Sistemdeirigatii/pornire.php";
String URL2 = "http://192.168.0.73/Sistemdeirigatii/debit.php"; 
String URL3 = "http://192.168.0.73/Sistemdeirigatii/umiditate.php"; 
String URL4 = "http://192.168.0.73/Sistemdeirigatii/oprire_pompaArd.php";
String URL5 = "http://192.168.0.73/Sistemdeirigatii/activitate_pompa.php";
String URL6 = "http://192.168.0.73/Sistemdeirigatii/update_stats.php";
String debit, umiditate;
int debit2, umiditate2;
#define pompPin 16
#define pompPin2 17
const int umidPin = 35;
const float voltageDividerRatio = 6.5;
const int sensorBatery = 34;
const float minVoltage = 10.0;
const float maxVoltage = 12.41;
const int umiditatemin = 20;
const int wet = 2200;
const int dry = 4100;
const int debitPin = 14;
volatile long pulse = 0; 
float volume = 0.0;

int actualizareBaterie;
int actualizareUmiditate;

void setup() {
  Serial.begin(9600);
  pinMode(pompPin, OUTPUT);
  pinMode(pompPin2, OUTPUT);
  pinMode(sensorBatery, INPUT);
  attachInterrupt(digitalPinToInterrupt(debitPin), increase, FALLING);
  connectWifi();
}

void loop() {
  HTTPClient http;
  HTTPClient http2;
  HTTPClient http3;

  actualizareUmiditate = citireUmiditate();
  actualizareBaterie = citireBaterie(34);
  http.begin(URL);

  int httpCode = http.GET();
  String Status = http.getString();
  int status = Status.toInt();

  Serial.println(citireUmiditate());
  Serial.println(actualizareBaterie);
  if (citireUmiditate() <= 20) {
    status = 2;
    // scriere in baza de date
  }

  switch (status) {
    case 1:
  pornirePompa();
  pulse=0;
  do {
    http2.begin(URL);
    int code = http2.GET();
    if (code > 0) {
      Status = http2.getString();
      if (Status.toInt() != 1) { 
        break;
      }
    }
    http2.end();

  } while (true); 
    Serial.println(2.336*pulse);
  oprirePompa(); 
  Serial.println("M-am oprit");
  break;
    case 2:
      readHumidityAndControlPump();
      break;
    case 3:
      readDebit();
      break;
    case 0:
      updateStats();
      Serial.print("...");
      delay(1000);
      break;
  }
}

void connectWifi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  WiFi.mode(WIFI_STA);

  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.print("connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

int citireUmiditate() {
  int sensorVal = analogRead(umidPin);
  int percentageHumididy = map(sensorVal, wet, dry, 100, 0);
  return percentageHumididy;
}

int citireBaterie(int pin) {
  int sensorValue = analogRead(pin);

  float batteryVoltage = (sensorValue / 4095.0) * 3.3 * voltageDividerRatio;
  Serial.println(batteryVoltage);

  if (batteryVoltage >= maxVoltage) {
    return 100;
  } else if (batteryVoltage <= minVoltage) {
    return 0;
  } else {
    return (int)((batteryVoltage - minVoltage) * 100.0 / (maxVoltage - minVoltage));
  }
}

void updateStats() {
  HTTPClient http;
  http.begin(URL6);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  String postData = "procent_baterie=" + String(actualizareBaterie) + "&umiditate_teren=" + String(actualizareUmiditate);
  int httpResponseCode = http.POST(postData);

  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Response: " + response);
  } else {
    Serial.println("Error on HTTP request: " + String(httpResponseCode));
  }

  http.end();
}

void readDebit() {
  HTTPClient http3;
  http3.begin(URL2);

  int httpResponseCode = http3.GET();
  if (httpResponseCode > 0) {
    String debit = http3.getString();
    debit2 = debit.toInt();
  } else {
    Serial.println("Error on HTTP request: " + String(httpResponseCode));
  }

  http3.end();
  pornirePompa();
  Serial.println(debit2);
  pulse = 0; 
  while (2.663 * pulse < debit2) {
    
  }
  oprirePompa();
   HTTPClient http4;
  http4.begin(URL4);
  http4.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String postData = "status=0"; 
  int httpResponseCode2 = http4.POST(postData);

  if (httpResponseCode2 > 0) {
    String response = http4.getString();
    Serial.println("Status updated: " + response);
  } else {
    Serial.println("Error on HTTP request: " + String(httpResponseCode2));
  }

  http4.end();
}

void readHumidityAndControlPump() {
  HTTPClient http2;
  http2.begin(URL3);
  pulse=0;
  int httpResponseCode = http2.GET();
  if (httpResponseCode > 0) {
    String umiditate = http2.getString();
    umiditate2 = umiditate.toInt();
    Serial.println("Umiditate inițială: " + umiditate);
  if (citireUmiditate() < umiditatemin)
  pornirePompa();
    while (citireUmiditate() < umiditatemin) {
      httpResponseCode = http2.GET();
      if (httpResponseCode > 0) {
        umiditate = http2.getString();
        umiditate2 = umiditate.toInt();
        Serial.println("Umiditate actualizată: " + String(umiditate2));
      } else {
        Serial.println("Error on HTTP request: " + String(httpResponseCode));
        break; 
      }
    }
    oprirePompa();
    Serial.println("Am terminat");
  } else {
    Serial.println("Error on initial HTTP request: " + String(httpResponseCode));
  }

  http2.end();
}

void pornirePompa() {
  pulse = 0;
  digitalWrite(pompPin, HIGH);
  digitalWrite(pompPin2, LOW);
}

void increase() {
  pulse++;
}

void oprirePompa() {
  digitalWrite(pompPin, LOW);
  digitalWrite(pompPin2, LOW);
  volume = 2.663 * pulse; 
  Serial.print("Volume: ");
  Serial.println(volume);

  
  HTTPClient http;
  http.begin(URL5);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String postData = "volume=" + String(volume); 
  int httpResponseCode = http.POST(postData);

  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Response: " + response);
  } else {
    Serial.println("Error on HTTP request: " + String(httpResponseCode));
  }

  http.end();
}
