# Hummus Indexer
Gets all messages in a channel through Hummus API (which is based on Discord V6 API structure) and then stores them in a MySQL database.

# How to use
Upload hummusindexer.php to your site.<br>
Create the database, and import the structure of db.sql into it.<br>
Fill in the database details.<br>
Get the token for your Hummus user account (this allows indexing of DMs you may have and GCs you may be in), and fill in the proper variable in the file.<br>
Put the ID of the server channel / direct message / group chat you want to index in the proper variable. (Note: The account you used must have access to the channel on Hummus.)<br>
Save the file, and then visit it in your browser.<br>
Repeat for each channel you want to index by simply changing the channel ID variable and then revisiting the page.
