**Day 28 - Basic API Authentication**

**Welcome class. Review API endpoints and Post types in PHP. Today we are going to cover API Sessions and Basic Authentication.**

1. **JWT Plugin for WP** - [https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/) 

    1. Download and install plugin on wordpress, then enable it

    2. Follow directions in readme to modify the .htaccess file

        1. Make sure the rule is copied close to the top, just after the first RewriteEngine On

        2. Be sure to save a copy of your htaccess in a separate file called  htaccess.example so that you can re-apply it if you ever change the permalinks because wordpress will overwrite it.

    3. add secret key to wp-config.php

        3. there is a link in the readme to help you generate a strong key

        4. you can copy one of the keys as your secret key

2. **To ensure that you have done this correctly, you have to test it using postman**

    4. Create a POST to [your-project-url]/wp-json/jwt-auth/v1/token

    5. under headers, make sure your content-type is set to application/json

    6. Then, under the body tab, you need the following:
{
	"username": "your_username_here",
	"password": "your_password_here"
}

3. **When you have confirmed that this is working, you will need to add the authentication in React**

    7. **First, in your appContext, you need to generate the token**

        5. **Just like in postmen, you need to send a post request to get the token when the user logs in so that we can store their token.**$a**

            1. **Example:**
**fetch('/user/data', {**
  **method: POST,**
  **headers: {**
    **‘Content-Type’: 'application/json’**
  **},**
  **body: JSON.stringify({username:** **‘your_user’,password:   ‘your_pass’})**
**})**
**.then(res => res.json())**
**.then(data => { **//method to store user token in store** })
**.catch(err => { console.log(err) })**

2. **This will ensure that you have a token stored in your application store for use later.**

    8. **Then you need to make sure any future fetch requests to protected routes contain the new token.**

        6. **This is done by adding the authorization header to your fetch request. If this is not done, protected resources cannot be accessed. You will get an error 403.**

        7. **Example:**
**fetch('/user/data', {**
  **method: 'GET',**
  **headers: {**
    **'Authorization': 'Bearer' + **this.state.store.authToken**
 **}**
**})**
**.then(res => res.json())**
**.then(data => { console.log(data) })**
**.catch(err => { console.log(err) })**

**Assignment:** For the rest of class, I would like you to work on your user authentication. You should add JWT to your project and make sure that have the fetch set up for getting token and build at least 1 get request using the token in the header. (this way you have a template for other requests you work on)