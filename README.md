# VENUS Appliance

VENUS is a Virtual Enrollment Notification and Update System created to make the process of recruitment for clinical research studies more user friendly. It's an easy button for research!

### Clinical Workflow

A clinician identifies a patient that may be eligible for a research study. The clinician pushes the buttons that correspond to the study on the keypad followed by the # key. This sends a message to one or more study coordinators by pager, sms, and/or email. The study coordinator then finds the clinician and gathers the appropriate information. No personal health information/identification is sent via VENUS.

### Technical Workflow

VENUS uses devices in the [Particle](http://particle.io) family of projects (photon and electron) to interpret keypresses and send information (`keypad.ino`), via webhook (`venus_electron.json`), to a predefined php script (`notify.php`). The script matches the keypress to a particular study (`test_studies.xml`) and sends sms and email messages to the defined study coordinators using the [Twilio](http://twilio.com) and [Postmark](http://postmarkapp.com) APIs, respectively.

# Assembly Instructions
### Tools &amp; Materials
##### Required
- [Particle Photon or Electron](https://store.particle.io/)
- 4x4 membrane keypad ([Ebay](http://www.ebay.com/sch/i.html?_nkw=4x4+membrane+keypad)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=4x4+membrane+keypad)), ([Adafruit](https://www.adafruit.com/products/419) 3x4)
- Micro USB cable ([Ebay](http://www.ebay.com/sch/i.html?_nkw=micro+usb+cable)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=micro+usb+cable)), ([Adafruit](https://www.adafruit.com/products/2185)) and some sort of usb power adapter ([Ebay](http://www.ebay.com/sch/i.html?_nkw=usb+power+adapter)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=usb+power+adapter))
- X-Acto (or other sharp) knife
- 8 long male headers. [I used these from Sparkfun](https://www.sparkfun.com/products/12693).
- Wire cutters
- A webserver capable of running PHP. I use [Dreamhost](http://dreamhost.com).

##### Optional, but may make things easier
- Drill with a set of small drill bits.
- Dremel
- Hot glue gun
- The enclosure of your choice. I used [this one from McMaster-Carr](http://www.mcmaster.com/#catalog/122/894/=128k2f4). In a revised version, I simply used the plastic case that came with the Electron and it worked beautifully. I outline that process below.

## Installation and Assembly
### Setting up the Webserver
- change notify.php to include your twilio settings
- edit the configuration files

### Setting up the Third Party Services
##### Postmark
##### Twilio
##### Pushover

### Setting up the Particle Electron
- claim the electron
- install the sim card and register
- create the webhook
- modify keypad.ino
- flash the electron

### Building the Case
- modify the case
- place the breadboard
- place the electron
- place and connect the antenna
- place and connect the keypad
- plug in and close the case

## What I've learned. Ideas for future development and enhancement. 
- Put third party API keys in a config file and push the name of that config file using the webhook.
- Build a password protected index.html page where config files and legends can be viewed.


