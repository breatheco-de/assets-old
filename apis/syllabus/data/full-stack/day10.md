**Day 9 -  DOM**

**Welcome class. Take any questions about javascript (10 min)**

**Run the Collaborative project to see how it turned out. (5min)**

**Remind everyone that this weekend, they should go to the downtown 4geeks to work on their code and get help.**

1. **Main website events: PreLoad & OnLoad** **[https://breatheco.de/en/lesson/events/]**(https://breatheco.de/en/lesson/events/)

    1. When we began javascript, we covered that there is a timeline for the loading of a document.

        1. Preload -> Load -> Render

        2. Preload is typically server related stuff. This is where Wordpress, PHP, Python, Node.js, etc. live. 

        3. It’s also where any API endpoints are created. API is something that allows you to communicate with an application by commands. (it’s how we get data from databases)

        4. onLoad is where the document begins to load it’s scripts and necessary files, as well as begin render.

2. **The-Runtime (after onload)**

    1. The Runtime takes place after load/render, during the application life cycle

    2. Basically, it’s everything that happens while your application is running.

    3. The DOM is rendered at run-time. 

3. **Introduce the DOM** **[https://breatheco.de/en/lesson/the-dom/]**(https://breatheco.de/en/lesson/the-dom/)

    1. Document Object Model - The DOM is basically like a map of all elements on your site and the events that take place for each of them

    2. Elements can be targeted and then properties can be set or modified on the fly.

    3. This is the main way that we are able to create content on the fly with Javascript

    4. Talk about event listeners

        1. Listeners allow us to set a function that will listen for an event that we specify and then do some action that we would like to perform

        2. For example, you may have seen "onClick" in html. That is basically a listener on a button or element.

        3. In Javascript, we can set a listener on the click of any element in the DOM and then react to that click. We can even eliminate the default behavior. (this is really good for changing what anchors and buttons do)

4. **Use querySelector() to select DOM Elements just like you do with CSS**

    1. querySelector allows us to directly target an element by using the ID or Class of the element. 

        1. document.querySelector(‘#myID’).style.backgroundColor = ‘black’;

    2.  We can also target elements by element name

        1. document.querySelector(‘p’).innerHTML = "Hello, World!";

    3.  These elements can be stored in variables for use later.

        1.  var element = document.querySelector(‘#myID’);

        2.  this gives you access to the entire object and all of its properties.

5. **Add/Remove CSS Classes to DOM elements**

    1.  There are a couple of ways to do this.

    2.  First either way starts with selecting the element (use querySelector or getElementById)

    3.  Then you can either:

        1.  use .addClass and .removeClass on your element 

        2.  or in pure Javascript

            1. element.classList.add("className");

            2. element.classList.remove("className");

        3.  use jQuery and .toggleClass()

            1. this is a good method, but requires you to include jQuery in your document.

            2. It also requires using jQuery syntax which is slightly different than javascript.

        4.  For now, I would recommend getting comfortable with .addClass and .removeClass. 

6. **Webpack**

    1.  Introduce what webpack is and how it will help them

        1.  Webpack is a bundler that groups all of your application pieces (Files, Images, Fonts, JS, CSS, HTML, etc.) into one big file

        2.  Cover why it’s important (from [https://breatheco.de/en/lesson/webpack/](https://breatheco.de/en/lesson/webpack/) )

    2.  We will be using it for all of our react projects.

    3.  For the Config, review the webpack lesson and go through the process exactly as mentioned.

7. **Bundling JS, CSS & Images.**

    1.  If you are including files other than JS, you need to use either

        1.  include or require

        2.  must get a "loader" to process the type of file.

8. **Include your bundle on index.html**

    1.  If you looked at the vanillaJS boilerplate, you may have noticed that on your index.html, there is a script include for bundle.js

    2.  This is how webpack is included in your project.

    3.  If this is missing, your bundle won’t load.

**Today we are going to start the Random Card Project.**

**Please read the Readme file and watch the video for the project that is in your day 9 on breathcode.**

**Try to break the project up and plan it in steps:**

* **First I need X, then I use X to do Y.**

* **If you have questions, please ask, but I would like you to try on your own first.**

* **Hitting the wall is important for your growth as a developer. You will always learn more from doing something wrong first and then finding your way then you will from being given the solution.**

**Homework: **Finish Random Card and all previous exercises.  Catch up on any repl’its you are missing.** STUDY AND READ**