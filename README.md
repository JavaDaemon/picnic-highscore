Picnic Highscore
================

A super-simple RESTful highscore written in PHP
 
This is a light script that you can put on your webserver, and it will turn it into an online highscoring table.
The table through the HTTP protocol, using GET and POST requests to interract with it. All there is left to do, is to implement the client where you need it.


Getting started
---------------

The software contains just one core PHP script. 
It is important that you specify your MySQL database within that file, as well as
make sure your database has the table "highscores", setup correctly. 
The table needs to have the following fields:

<table>
    <tr>
        <td><b>Name</b></td>
        <td><b>Type</b></td>
        <td><b>Notes</b></td>
    </tr>
    <tr>
        <td>id</td>
        <td>INTEGER</td>
        <td>Auto-incremented</td>
    </tr>
    <tr>
        <td>name</td>
        <td>TEXT</td>
        <td>-</td>
    </tr>
    <tr>
        <td>score</td>
        <td>INTEGER</td>
        <td>-</td>
    </tr>
</table>
If you wish to add or change anything, feel free to do so. I tried to make
it easy to extend and rework things, such as the JSON required, and the JSON output.

Implementing the client
-----------------------
To interract with the highscore, all you have to do is send HTTP requests to it's location on your webserver.
Make sure to specify the request type.

### GET ###
Sending a GET-request to the highscore will output JSON formatted like so:
```
[
    {
        "name": "Best player",
        "score": "100"
    },
    {
        "name": "Second best player",
        "score": "50"
    },
    {
        "name": "Third best player",
        "score": "23"
    }
]
```

### POST ###
Sending a POST-requests requires you to specify the content-type in the header to:
```
application/json
```
You are also required to send JSON as your content, formatted like so:
```
{
	"name" : "an example name",
	"score" : "230"
}
```

### Errors ###
If anything ever goes wrong in your interraction with the highscore, an HTTP status code will be returned, explaining the error. Be sure to check if this is the case, if you are having trouble implementing the highscore.
