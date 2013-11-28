Picnic Highscore
================

A super-simple RESTful highscore written in PHP


Getting started: 
The software contains just one core PHP script. 
It is important that you specify your MySQL database within that file, as well as
make sure your database has the table "highscores", setup correctly. 
The table needs to have the following fields:
	- id. Integer. Primary key. Auto-incremented.
	- name. Text. 
	- score. Int.
If you wish to add or change anything, feel free to do so. I tried to make
it easy to extend and rework things, such as the JSON format required and output.
