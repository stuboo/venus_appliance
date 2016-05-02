# VENUS Appliance

VENUS is a Virtual Enrollment Notification and Update System created to make the process of recruitment for clinical research studies more user friendly. It's an easy button for research!

### Clinical Workflow

A clinician identifies a patient that may be eligible for a research study. The clinician pushes the buttons that correspond to the study on the keypad followed by the # key. This sends a message to one or more study coordinators by pager, sms, and/or email. The study coordinator then finds the clinician and gathers the appropriate information. No personal health information/identification is sent via VENUS.

### Technical Workflow

VENUS uses devices in the [Particle](http://particle.io) family of projects (photon and electron) to interpret keypresses and send information (`keypad.ino`), via webhook (`venus_electron.json`), to a predefined php script (`notify.php`). The script matches the keypress to a particular study (`test_studies.xml`) and sends sms and email messages to the defined study coordinators using the [Twilio](http://twilio.com) and [Postmark](http://postmarkapp.com) APIs, respectively.

### Hardware

- [Particle Photon or Electron](https://store.particle.io/)
- 4x4 membrane keypad ([Ebay](http://www.ebay.com/sch/i.html?_nkw=4x4+membrane+keypad)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=4x4+membrane+keypad)), ([Adafruit](https://www.adafruit.com/products/419) 3x4)
- The enclosure of your choice. I used [this one from McMaster-Carr](http://www.mcmaster.com/#catalog/122/894/=128k2f4)
- Micro USB cable ([Ebay](http://www.ebay.com/sch/i.html?_nkw=micro+usb+cable)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=micro+usb+cable)), ([Adafruit](https://www.adafruit.com/products/2185)) and some sort of usb power adapter ([Ebay](http://www.ebay.com/sch/i.html?_nkw=usb+power+adapter)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=usb+power+adapter))

