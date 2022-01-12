# TrueUser Contact Form
 
Have you ever wanted a contact form that would tell you everything (within the bounds of the law) you could possibly know about the person sending the message? Have you also ever wanted to screw with abusive users? If so, TrueUser Contact Form might be for you! By integrating this piece of software into your website, you will be able to get the following information about the person who is sending messages through your contact form:

1. Their IP address, along with a link to get more information about that IP address from Scamalytics, a database that can tell you if an IP is legit or is a fraudulent VPN/VPS connection.
3. The time from the visitor's PC system clock along with its set timezone. If a user's IP is located in Spain but their system clock is showing them coming from Australia, you'll know something's up.
4. If a visitor has tracking scripts enabled in their browser, allowing you to determine who's trying to stay hidden.
5. The visitor's screen height and width in pixels. If a user is supposedly coming from IP addresses and countries all over the world but every single one of those users seems to have the exact same device screen size, you might be able to use that information to connect abusive users.
6. If a sender moved their device's mouse before sending their message, something which can tell you if a user is real or if they're a bot (reported as a Boolean).
7. If a user has scrolled the page, along with how many times they have done so, something which can help you separate real users from bots if their device uses a touchscreen.
8. The keyboard keys a user pressed before sending their message, allowing you to see if their message was copy-and-pasted or if it was composed by hand (soon will include modifier keys like control, alt, etc).
9. How long a user was active on the page before they sent their message, revealing whether or not a bot sent the message in a millisecond or if someone actually spent time writing it out.
10. The number of CPU cores on the user's computer – a data-point which can be used to connect multiple visitors reporting different IPs.
11. The color and pixel depth of the user's computer – another data-point which can be used to connect multiple visitors reporting different IP addresses.
12. Whether or not a visitor has used the contact form before by setting a unique cookie disguised as a legitimate cookie on their computer, allowing you to connect users by cookie value, even if they're coming from different IPs.

The contact form also provides a number of other protections against abusive users, such as:
1. Verifies that a user's email address is real by only allowing a message to be sent after the user inputs a code that is sent to their address.
2. Prevents users without JavaScript from using the form altogether (the form's HTML code is loaded with JavaScript, meaning that the form doesn't even technically exist if the user doesn't have JavaScript enabled).
3. The contact form HTML code is stored externally and does not include its own submit input, meaning that a potentially malicious user can't visit the form code HTML page directly and send messages through there.
4. Prevents users without cookies enabled from using the form, deterring users who don't want to compromise their anonymity from sending messages.
5. An option to prevent users with trackers disabled from using the form, deterring users who don't want to compromise their anonymity from sending messages.

The contact form also allows you to crash the user's browser/tab by entering their IP address in the settings.xml file. It will output a whole bunch of scary-looking dummy code on the user's screen, and then will trigger an infinite JavaScript loop that will eventually exhaust the malicious user's system memory. The form will also include a Hall of Shame, where identified malicious users can be named and shamed for eternity by clicking the Hall of Shame link beneath the 'Submit' button.

In addition, the form can be configured with optional limits on how many messages an IP address can send per day, how many verifications can be sent to an email address per day, what the cool-off period should be before a user can send another verification code, and so on, to further prevent online harassment.

The form is in its early stages and is not finished. I may never finish it, hence why I'm uploading the code here. A future version of the script will include a fancy, drag-and-drop form builder designed using Bootstrap, something which is already 90% done.

Each email and verification code sent is logged in encrypted text files, organized by day. The master key for this encryption will eventually be stored outside the document root, preventing malicious users from discovering it.

# Screenshots of Incomplete Builder Version

<center>
<img src="https://github.com/jm11116/TrueUser-Contact-Form/blob/main/screenshots/Screen%20Shot%202022-01-12%20at%204.10.53%20PM.png" style="max-width:80%">
<img src="https://github.com/jm11116/TrueUser-Contact-Form/blob/main/screenshots/Screen%20Shot%202022-01-12%20at%204.11.34%20PM.png" style="max-width:80%">
<img src="https://github.com/jm11116/TrueUser-Contact-Form/blob/main/screenshots/Screen%20Shot%202022-01-12%20at%204.18.23%20PM.png" style="max-width:80%">
</center>
