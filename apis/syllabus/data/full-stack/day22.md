**Day 22 -  Intro to AJAX/Fetch**

**Welcome class. Quick recap of Context API (5min). Today we will be discussing AJAX (Asynchronous JavaScript And XML) and Fetch.**

1. **Intro to REST:**

    1. **Re-Explain HTTP Requests**

        1. HTTP works as a request-response protocol between a client and server.

        2. Typically, when we structure a request, we will do so with any of 4 types. 

        3. This allows us to perform operations on our API’s.

        4. There is a really great write-up on W3schools for http methods if you would like some extra reading: [https://www.w3schools.com/tags/ref_httpmethods.asp](https://www.w3schools.com/tags/ref_httpmethods.asp) 

    2. **4 Operations (C.R.U.D.):**

        5. **GET (Read/Retrieve) -** **GET allows you to read/retrieve data into your application.**

        6. **POST** **(Create)**  - **POST is used to send data to a server to create a resource.**

        7. **PUT** **(Update)** **-** PUT is used to send data to a server to update a resource.

            1. the main difference between PUT and POST is that PUT is idempotent, meaning that if you do PUT multiple times with the same action, it has no negative effect. It will always produce the same result. 

            2. There is a great discussion here about PUT and POST. The first answer has over 3000 upvotes and covers most of the differences:

                1. [https://stackoverflow.com/questions/630453/put-vs-post-in-rest](https://stackoverflow.com/questions/630453/put-vs-post-in-rest) 

        8. **DELETE.  -** DELETE allows you to delete or remove the specified resource.

    3. **AJAX is a method of calling data in your application during runtime but without refreshing the page. This is often used in non-react applications to load items inline.**

        9. A good example would be a shopping cart. 

            3. when you click to add a product, the number on the cart updates that you have added an item.

            4. What has actually happened is that they sent a request to the cart API that told the server to add an item to the cart

            5. Then they refreshed your cart on the page to reflect your item count and any other features they want to display.

            6. Not bad for something as simple as just changing a number on a page, huh? 

        10. Let’s look at a code example

            7. [https://www.w3schools.com/js/tryit.asp?filename=tryjs_ajax_callback](https://www.w3schools.com/js/tryit.asp?filename=tryjs_ajax_callback) 

2. **Now, How about Fetch?**

    4. Fetch is another communication protocol which we will be using to access data instead of ajax

    5. Example: [https://developers.google.com/web/updates/2015/03/introduction-to-fetch](https://developers.google.com/web/updates/2015/03/introduction-to-fetch) 

    6. **.Then()**

        11. Then is where you put all of your functions that should occur after the resource is fetched

        12. Typically, you will have a condition that breaks if there is any status other than a 200, which is a success. 

        13. Also, you need to tell your fetch what to do with the data, such as output them or move them to your store.

    7. **.Catch()**

        14. This is our error handler

        15. Up until now, we have only cared about dealing with actions, but now you are not fledgling programmers, you are budding Web Developers.

        16. It’s important to tell your fetch how to handle errors.

3. **JSON, instead of HTML will now be your main communication format. **

    8. **JSON is a Javascript object but as TEXT**

        17. When you view a JSON, it’s a bundle of objects.

        18. Example: [https://github.com/breatheco-de/breathecode-cli/blob/master/boilerplates.json](https://github.com/breatheco-de/breathecode-cli/blob/master/boilerplates.json) 

        19. Here you see the json that governs our breatheco.de cli boilerplates. This file is read by the CLI and used to create your boilerplate projects.

4. **Postman**

    9. **What is it?**

        20. Postman is a program that allows you to run tests and simulations on your API endpoints.

        21. You can create tests that will show you the JSON output that an API is returning.

    10. **How to use POSTMan, set environment variables and use collections**

        22. **Using POSTMan**

            8. **First thing you want to do is create a collection**

                2. A collection is a group of tests that are related

                3. For example, you can create tests for your specific api calls to wordpress and see what those tests return

                4. To create one, you just click new and go to Collection, name it and you are good to go.

            9. **In your collection, you can add tests.**

                5. each test is formed as a request you will make to your API endpoint

                6. You choose the type of request GET,PUT,POST, DELETE and then setup where it is communicating to and the parameters

                7. In your headers, you need to set your Content-Type(key) and application/json (value)

            10. **Another thing that may come in handy is setting environment variables.**

                8. An environment variable is something you will use often.

                9. An example is your hostname. Once you set your host as an environment variable, you just have to call it and never type the hostname again.

                10. Go to the gear in the top right, then manage environments

                11. add a new environment or select your current project (you can also set up global variables for postman)

                12. set up your variables in key value pairs.

                13. For the host example, you can do something like:

                    1. Key: host

                    2. Value: //Your url for your api

                14. Then when you want to access it, you would just use {{host}}

5. **The goal is to send/receive everything as JSON Serialize>Send>Unserialize**

6. **What is serialization and how to do it (Parsing)**

    11. Serialization is the method in which we take data and convert it to a format that we can use

    12. With Javascript and JSON, we would take the object data we would like to serialize and use JSON.Stringify() to convert it to a string. 

        23. [https://www.w3schools.com/js/js_json_stringify.asp](https://www.w3schools.com/js/js_json_stringify.asp) 

        24. typically, this serialization is server side though, so what will happen with wordpress is that the data is already serialized and you will just have to fetch and unserialize.

    13. You then transmit it. In our case, as mentioned, we use Fetch to grab the data from our endpoint. 

    14. If we were performing a standard AJAX request, we would then use JSON.Parse() to de-serialize the data.

        25. [https://www.w3schools.com/js/js_json_parse.asp](https://www.w3schools.com/js/js_json_parse.asp) 

        26. Using dot notation in our javascript, we can now access the properties of the json object we converted.

    15. With Fetch, we don’t have to worry about parsing. Fetch takes care of the parsing when you call response.json() 

7. **Why using several request types (GET, POST, PUT, DELETE)?**

8. **Explain the 3 mains types content-types:**

    16. **Form - (multipart/form-data)** With this method of transmitting name/value pairs, each pair is represented as a "part" in a MIME message. Each part has it’s own header that tells it what to do. 

        27. this method is more effective for larger form data

    17. **URL-Encoded - (application/x-www-form-urlencoded) Essentially returns as one big query string in the browser header.**

        28. Question mark (?) starts the query string

        29. key/value pairs are separated by the ampersand (&)

        30. keys are separated from their corresponding values by the equals symbol (=)

        31. Example:    ?name=John&age=32&sex=male

        32. Can be used for smaller queries or short form data but isn’t very secure because it exposes your values through the browser.

    18. **Raw (With JSON) - (application/json)** This is by far the best method of working with your data. As we discussed in this lesson, working with JSON allows you to format everything in key value pairs and compose a set of data that can be decoded an accessed efficiently.

As main class exercise I always like to ask students to do a fetch request to this url: [https://assets.breatheco.de/apis/fake/weird_portfolio.php](https://assets.breatheco.de/apis/fake/weird_portfolio.php)

And then try grabbing information from the incoming json object, e.g: You can ask them to get the thumbnail of the second image of the post with ID 139.

The exercise is great because the response is really complex with nested objects, they will practice a lot.

Also you can get the bitcoin price from coinmarketcap api.