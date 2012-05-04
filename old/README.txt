README

This file is still draft, maybe some day it will be filled with some interesting info.

If you have unpacked all the content to one directory, just browse to the install location of your game.

And please, read manual!

To install MySQL database >= 4.1 you should use UTF-8 charset format! Unfortunately it would be breaking the standard of MySQL data files so you in that case the database should be reinstalled and there will be no compatibility with MySQL < 4.1 data files. You can also use latin1 or latin2 charset (in that case data files will be compatible) but if any illegal character found SQL query it will be ignored... ;(

If you use MySQL < 4.1 backups can be made directly by copying data files from MySQL datadir (/var/lib/mysql/ or "C:\Program Files\MySQL\data\").

If running GF on a machine < 1 GB RAM it is recommended to set output_buffering value in your PHP parser to 16KB (16384) or even 64KB (65536). If your machine is dedicated web server you can also turn on zlib compression (useful for sending data to the world). 

Here is my example from home server:

output_buffering=32768
zlib.output_compression=On
