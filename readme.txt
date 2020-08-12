I used the software stack WampServer to develop and test the application. It uses an Apache server, php, and a MySQL database.
To set up the application put the included server files on an Apache server and create a sql database called ticketingdb.
Import/run the included sql file on that database. If the server and database are running the app can be accessed with a browser. 

In WampServer the server files can be put in C:\wamp64\www\ssd. The website would then be accessed with localhost/ssd.

An account should already exist:
username: admin
password: password
(the credentials are simple for the sake of testing; the user creation form requires password complexity)

This account has admin rights and can create new users. The homepage displays a list of tickets which should be empty at the start.
New users and tickets can be added with the buttons on home.php. Existing tickets can be edited by clicking the little
buttons in front of each row.