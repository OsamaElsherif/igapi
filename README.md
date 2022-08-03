## this is the script for getting the instagram stories and save it
### Documintaion

download this folder, and make changes in the `get_information.php`
you have to clone the `php-garph-sdk` from the main repo to this project folder
```
git clone https://github.com/facebookarchive/php-graph-sdk.git
```
you have to use the API endpoints to make integrations with other programming langyages

> # *** REMEMBER THAT THE SCRIPT WILL CREATE DIRS & FILES FOR ALL THE USERS WHO WILL USE YOUR APPLICATION, WHICH NEANS THAT YOU NEED TO GET THEIR PERMESSION FOR GETTING THEIR DATA ***

to start the script :

1. create a file that will intilize the whole script like `main.php` and `get_information.php`
2. save the token in a session and use it in the API endpoints, then redirect the page to the intialized file for your project.
3. use the IGAPI main classes to make you requests or use the API

## *endpoint for the API*

1. https://`server_name`/account/`token`/ or https://`server_name`/account/`token`/me 
getting your information and there is a `?fields=` parameters you can specify which data you want to retrieve
2. https://`server_name`/account/`token`/me?username=''
searching for a public account via username and there is a `?fields=` parameters you can specify which data you want to retrieve
3. https://`server_name`/account/`token`/stories
getting the stories for the loged in account and saving it in a `/users/` directory and every user will have a directory with his instagram id
> */users/`ig_id`* all the directories and files will be created automatically if they are not exist
4. https://`server_name`/login
getting the url for the endpoint for logging in and getting the account access
> Logging in proccess has to be on a browsers for a session purpnoses. However, you can get the token as it's returned.
5. https://`server_name`/search/`username
searching got the users in the system and get their information
> the default way is in a json file `users.jsoo`. However, you can save the data in a database and create you own way to search for it, feel free to make the changes in the `API/src/Controllers/SearchController.php`

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
