/* @file keypad.ino
|| @version 1.0
|| @author Ryan Stewart, base code from Alexander Brevig
|| @contact ryan@ryanstewart.com
|| @contact alexanderbrevig@gmail.com
||
|| @description
|| | Uses the arduino keypad library to get a series of numbers/letters from the 4x4 membrane keypad. 
|| | Then sends that string by webhook to a php script (notify.php)
|| #
*/
#include "Keypad/Keypad.h"

const byte ROWS = 4; //four rows
const byte COLS = 4; //three columns
char keys[ROWS][COLS] = {
  {'1','2','3', 'A'},
  {'4','5','6', 'B'},
  {'7','8','9', 'C'},
  {'*','0','#','D'}
};
byte rowPins[ROWS] = {D0, D1, D2, D3}; //connect to the row pinouts of the keypad
byte colPins[COLS] = {D4, D5, D6, D7}; //connect to the column pinouts of the keypad

Keypad keypad = Keypad( makeKeymap(keys), rowPins, colPins, ROWS, COLS );

void setup(){
  Serial.begin(9600);
}
  
void loop(){
    String value = "";
    String reset = "*-*-*-RESET-*-*-*";
    char key;
    while(true)
    {
        delay(20);
        key = keypad.getKey();
        if (key == NO_KEY) continue;
        if (key == '#') break; // send the key combination when the pound key is pressed
        if (key == '*') // if the star key is pressed, that's a reset
            {
                value = "";
                goto reset_button;
            }
        value += key;
        // TODO: display value here

       do
       {
           delay(20);
        }
        while (keypad.getKey() != NO_KEY); // wait until key is released
        //Serial.println(key);
    }
    Serial.println(value);
    // Call the webhook here and send the value to it.
    Particle.publish("venus", String(value), 60, PRIVATE);
    delay(5000);
    reset_button: // reset the value to empty
        Serial.println(reset);
        
  
}
