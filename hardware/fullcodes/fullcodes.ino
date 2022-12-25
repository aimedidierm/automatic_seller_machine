#include <ArduinoJson.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <Keypad.h>
#define SS_PIN 10
#define RST_PIN 9
MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance.

int Interrupt = 1;
int sensorPin       = 2;
int Valve = A3;
float calibrationFactor = 90;
volatile byte pulseCount = 0;
float flowRate = 0.0;
unsigned int flowMilliLitres = 0;
unsigned long totalMilliLitres = 270;
unsigned long oldTime = 0;
String data = "";
LiquidCrystal_I2C lcd(0x27, 20, 4); // set the LCD address to 0x27 for a 16 chars and 2 line display
int outml = 0;
const byte ROWS = 4; //four rows
const byte COLS = 4; //four columns
//define the cymbols on the buttons of the keypads
char newNum[12] = "", amount[12] = "", kwishyuraamount[12] = "";
//define the cymbols on the buttons of the keypads
char keys[ROWS][COLS] = {

  {'1', '2', '3'},

  {'4', '5', '6'},

  {'7', '8', '9'},

  {'*', '0', '#'}

};

byte rowPins[ROWS] = {8, 7, 6, 5}; //connect to the row pinouts of the keypad
byte colPins[COLS] = {A2, A0, A1}; //connect to the column pinouts of the keypad

String card;
Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

int drink = 0, drinkvolume = 0;


void setup()
{
  lcd.init();                      // initialize the lcd
  lcd.init();
  SPI.begin();
  Serial.begin(9600);   // Initiate a serial communication
  SPI.begin();      // Initiate  SPI bus
  mfrc522.PCD_Init();   // Initiate MFRC522
  pinMode(Valve , OUTPUT);
  digitalWrite(Valve, LOW);
  pinMode(sensorPin, INPUT);
  digitalWrite(sensorPin, HIGH);
  attachInterrupt(Interrupt, pulseCounter, FALLING);
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(1, 0);
  lcd.print("Automatic seller");
  lcd.setCursor(5, 1);
  lcd.print("machine");
  delay(3000);

}

void loop()
{
  readcard();
}

void(* resetFunc) (void) = 0;

void drinkout() {
  digitalWrite(Valve, HIGH);
  while (drinkvolume > 20) {
    if ((millis() - oldTime) > 1000)   // Only process counters once per second
    {
      detachInterrupt(Interrupt);
      flowRate = ((1000.0 / (millis() - oldTime)) * pulseCount) / calibrationFactor;
      oldTime = millis();
      flowMilliLitres = (flowRate / 60) * 1000;
      drinkvolume -= flowMilliLitres;

      unsigned int frac;
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("Thank you!");
      lcd.setCursor(0, 1);
      lcd.print(drinkvolume);
      pulseCount = 0;
      attachInterrupt(Interrupt, pulseCounter, FALLING);
    }
  }
  digitalWrite(Valve, LOW);
  resetFunc();
  delay(3000);
  resetFunc();
}

void readcard() {
  // Look for new cards
  int i = 0, j = 0, m = 0, x = 0, s = 0, money = 0;
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Tap card");
  delay(500);
  if ( ! mfrc522.PICC_IsNewCardPresent())
  {
    readcard();
    //return;
  }
  // Select one of the cards
  if ( ! mfrc522.PICC_ReadCardSerial())
  {
    readcard();
    //return;
  }
  String content = "";
  byte letter;
  for (byte i = 0; i < mfrc522.uid.size; i++)
  {
    content.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " "));
    content.concat(String(mfrc522.uid.uidByte[i], HEX));
  }
  content.toUpperCase();
  card = content.substring(1);
  kwishyura();
}
void kwishyura() {
  int j = 0, k = 0;
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Enter amount:");
  for ( int d = 2; d > 1; d++) {
    int key = keypad.getKey();
    if (key != NO_KEY && key != '#' && key != '*') {
      kwishyuraamount[j] = key;
      kwishyuraamount[j + 1] = '\0';
      j++;
      lcd.setCursor(0, 1);
      lcd.print(kwishyuraamount);
    }
    if (key == '#' && j > 0)
    {
      j = 0;
      lcd.clear();
      lcd.print("Loading");
      card.replace(" ", "");
      Serial.println((String)"?card=" + card + "&kwishyuraamount=" + kwishyuraamount); //kohereza data kurinodemcu
      while (k == 0) {
        if (Serial.available() > 0) {
          data = Serial.readStringUntil('\n');
          Serial.println(data);
          DynamicJsonBuffer jsonBuffer;
          JsonObject& root = jsonBuffer.parseObject(data);
          if (root["outml"]) {
            outml = root["outml"];
            if (outml == 1) {
              lowbalance();
            } else {
              drinkvolume = outml;
              drinkout();
            }
          }
        }
      }
    }
    delay(100);
  }
}
void lowbalance() {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Low balance");
  delay(3000);
  lcd.clear();
  resetFunc();
}
void pulseCounter()
{
  pulseCount++;
}
