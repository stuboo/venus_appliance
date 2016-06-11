# VENUS Appliance

VENUS is a Virtual Enrollment Notification and Update System created to make the process of recruitment for clinical research studies more user friendly. It's an easy button for research!

### Clinical Workflow

A clinician identifies a patient that may be eligible for a research study. The clinician pushes the buttons that correspond to the study on the keypad followed by the # key. This sends a message to one or more study coordinators by pager, sms, and/or email. The study coordinator then finds the clinician and gathers the appropriate information. No personal health information/identification is sent via VENUS.

### Technical Workflow

VENUS uses devices in the [Particle](http://particle.io) family of projects (photon and electron) to interpret keypresses and send information (`keypad.ino`), via webhook, to a predefined php script (`n.php`). The script matches the keypress to a particular study (`DEVICE_ID.json`) and sends sms and email messages to the defined study coordinators using the [Twilio](http://twilio.com) and [Postmark](http://postmarkapp.com) APIs, respectively.

# Assembly Instructions
### Tools &amp; Materials
##### Required
- [Particle Photon or Electron](https://store.particle.io/)
- 4x4 membrane keypad ([Ebay](http://www.ebay.com/sch/i.html?_nkw=4x4+membrane+keypad)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=4x4+membrane+keypad)), ([Adafruit](https://www.adafruit.com/products/419) 3x4)
- Micro USB cable ([Ebay](http://www.ebay.com/sch/i.html?_nkw=micro+usb+cable)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=micro+usb+cable)), ([Adafruit](https://www.adafruit.com/products/2185)) and some sort of usb power adapter ([Ebay](http://www.ebay.com/sch/i.html?_nkw=usb+power+adapter)), ([AliExpress](http://www.aliexpress.com/wholesale?SearchText=usb+power+adapter))
- X-Acto (or other sharp) knife
- 8 long male headers. [I used these from Sparkfun](https://www.sparkfun.com/products/12693).
- A webserver capable of running PHP. I use [Dreamhost](http://dreamhost.com). There are "free" PHP hosts out there, but I've never tried any of them.  If you do, please feel free to share your experience via pull request to this readme.

##### Optional, but may make things easier
- Drill with a set of small drill bits.
- Dremel
- Hot glue gun
- The enclosure of your choice. I simply used the plastic case that came with the Electron and it worked beautifully. I outline that process below.

## Installation and Assembly

### Setting up the Third Party Services
##### Postmark
- If you want to send emails from your VENUS device, you'll need a [Postmark](http://postmarkapp.com) account.
- [create a new Postmark account](https://account.postmarkapp.com/sign_up) - At the time of this writing, new users get 25,000 emails for free.
- log in to your newly created account and click 'Sender Signatures'
  - add a new signature
- log in to your newly created account and click 'Servers'
  - add a new server
  - after that server has been created, click 'credentials' and copy the `SERVER API TOKEN` -- you'll ultimately paste this into the `DEVICE_ID.json` file.
##### Twilio
- If you want to send SMS messages from your VENUS device, you'll need a [Twilio](http://twilio.com) account.
- [create a new Twilio account](https://www.twilio.com/try-twilio)
- log in to your newly created account and go to the [dashboard](https://www.twilio.com/console)
  - you will likely need to [add funds](https://www.twilio.com/console/billing) to your account
  - from the [dashboard](https://www.twilio.com/console), copy the `ACCOUNT SID` and `AUTH TOKEN` -- you'll ultimately paste both of these into the `DEVICE_ID.json` file.

### Setting up the Particle Electron
- [create an account with Particle](https://build.particle.io/signup) (if you didn't do it when you ordered a device)
- claim the electron
  - go to [setup.particle.io/start](http://setup.particle.io)
  - choose 'Setup an Electron w/SIM card' and follow the on-screen instructions
  - enter the ICCID number (19..22 digits) and click 'Next'
  - if things go as planned, your device will now be active and visible in your [particle dashboard](https://dashboard.particle.io/user/devices)
- [create a Particle webhook](https://dashboard.particle.io/user/integrations/create)
  - click 'Webhook' then click 'Custom JSON'
```
{
    "event": "YOUR-WEBHOOK-NAME",
    "url": "http://YOUR-WEBSITE-URL/n.php",
    "requestType": "GET",
	"query": {
		"id":"{{SPARK_EVENT_VALUE}}",
		"location":"{{SPARK_CORE_ID}}"
	},
    "mydevices": true
}
```
  - change `YOUR-WEBHOOK-NAME` to the name of your choice
  - change `YOUR-WEBSITE-URL` to the URL of the website you plan to set up in the next step
- modify (around line 60) keypad.ino to include `YOUR-WEBHOOK-NAME` that you chose above: `Particle.publish("YOUR-WEBHOOK-NAME", String(value), 60, PRIVATE);`
- navigate to [build.particle.io](https://build.particle.io)
  - choose the 'Devices' icon from the menu on the left and select your device (unless you have multiple devices, there will likely be only one option to choose from) -- the star beside the device should be yellow
  - choose the 'Code' icon from the menu on the left
    - paste the code from keypad.ino into the editor on the right side of the build.particle.io page
    - click the 'Verify' icon
    - click the 'Save' icon
  - choose the 'Libraries' icon from the menu on the left
    - under 'Community Libraries' type `keypad` into the search box
    - click KEYPAD
    - click the INCLUDE IN APP button
    - choose the KEYPAD app from the list
  - go back to the 'Code' view (by clicking the 'code' icon)
    - click the 'Flash' icon -- you should see a message at the bottom of the screen saying the flash was successful

### Setting up the Webserver
> A note about `DEVICE_ID.json` This file name will change for every device you set up. The `DEVICE_ID` is assigned by Particle, the makers of the photon and electron devices. To find the `DEVICE_ID` for all of the devices associated with your account, log in to the [Particle Dashboard](https://dashboard.particle.io/user/devices).
- change `DEVICE_ID.json` to include the location of the device, the location description, the callback number, and the 'ask for' value.
- change `DEVICE_ID.json` to include your Twilio and Postmark settings.
- upload the following files to your webserver
  - `/assets/`
  - `/files/`
  - `legend.php`
  - `n.php`
  - `DEVICE_ID.json` AFTER you changed the name to the actual device id. (ex `2300399944567gihew721127.json`)

### Building the Case
- modify the case
- place the breadboard
- place the electron
- place and connect the antenna
- place and connect the keypad
- plug in and close the case

## What I've learned. Ideas for future development and enhancement. 
- Build a password protected index.html page where config files and legends can be viewed.
- Set up basic http authentication in the webhooks for added security.


