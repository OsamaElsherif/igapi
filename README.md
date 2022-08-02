### this is the script for getting the instagram stories and save it

## TO DO LIST

1. clean the code [X]
2. create the needed oobjects [X]
3. make the project folder structure [X]


## Documintaion

Just you have to include the full php project to your project,
and create a json file called "defines.json", and copy the next
structure to it with you own app data.

# REMEMBER THAT THE SCRIPT WILL CREATE DIRS & FILES FOR ALL THE USERS WHI WOLL USE
# YOUR APPLICATION, WHUCH NEANS THAT YOU NEED TO GET THEIR ACCEPTACE FOR GETTING THEIR
# DATA

to start the script :

1. create a file that will intilize the whole script like main.php
2. make the file save the data in a session named data, and redirect the page to the start file for you project.
3. start the session and include the manager class from the utils folder
4. intialize the account class and feel free to do what you want

to get the account information : 

1. gust call the information method form the account instance
2. to get more information, just add the fields that you want to get, see the Meta Developers Docs to get more information about it

to search for someone: 
        !! The username account must be connected to facebook page !!

1. call the search method and write the username, and the fields you want to get 
2. to get more information, just add the fields that you want to get, see the Meta Developers Docs to get more information about it

to get the stories information : 

1. just call the stories method, and it will do all the things for you

---------------------------------------------------------------------------------------------

# THE STORIES CAN'T BE SAVED AS SOON AS THEY PUBLISHED WITHOUT WEEBHOOKS, AND WE USE WEBHOOKS
# JUST FOR GETTING THE FULL INSIGHTS AFTER THE STORY DISAPPERS, SO THERE IS AN OPTION CALLED
# HOURLU UPDATE WHICH WILL UPDATE THE STORY GETTING EVERY HOUR.

for the houly update you need to call the stories function hourly for every user, so you
have to put on your mind that this needs you to save the account instance to get to this data
the account instance be default saves the token so you will not get anytrouble to call different
instances at once, but this will make a huge trafic in your servers that goes to facebook.

1. create a json file that will hold all the accounts instance for 24H
2. save every account instance thtat have the stories array in it fill
3. call the get sroty for eact instance hourly
4. it will save the new stories
5. clear the json file for the next 24H