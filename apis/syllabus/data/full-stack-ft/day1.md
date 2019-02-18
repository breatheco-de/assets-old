**Day 1 - The Internet, HTML and CSS**

**6pm Small Presentation**

**Who we are**

* **Juan Carlo intro**

* **Daniela intro**

* **My intro**

**Get everyone to introduce themselves.**

* **First name and last**

* **What would you like to be called?**

* **What do you do now? (for work)**

* **What do you plan on doing when you become a developer?**

**Help them get started on cloud 9, slack, etc.**

* **Invite them to team on c9**

* **Don’t let them skip the steps on cloud 9 setup**

* **They have to click that they are a student and are part of a team**

**Sign an informal agreement with expectations**

1. **Client vs Server**

    1. **Explain how for student to access Google, their website is hosted on another computer called a "Server" that they own.**

    2. **The student would have to communicate from their local computer (called a "client"), to get the website from the server so they can view and use it.**

2. **HTTP Request vs Response**

    3. **The method used for this communication is called HTTP, which stands for Hyper Text Transfer Protocol.**

    4. **The way that you get the website is by sending a "Request" to the server. This tells the server what website or resource you are looking to view/use. The server then responds with the information.**

    5. *The server then sends a response, which tells will confirm the receipt of the request and let your computer know that it will begin sending data. (second day content)*

    6. *This back and forth process will continue with more Request=>Response pairs until you have finished loading your remote resource. (in this case, Google – second day content)*

3. **Everything is text!**

    7. **With HTTP, it’s important to focus on the first ‘T’ in the acronym, which stands for ‘Text’. In this protocol, everything is sent back and forth as TEXT.**

    8. **While there are other protocols out there, for now, we want to remember that with HTTP – Everything is TEXT.**

4. **Browser Interpretation**

    9. **After your browser uses the HTTP protocol and receives the data as text, it then has the job of interpreting that text and converting it to the correct visual format. (What do you think that text is converted into? Open your cheat sheet for HTML and guess. Images, tables, videos, animations, etc)**

5. **HTML vs CSS**

    10. **So that the developers that made Google (or any other website) can create the experience you know and love, they have to use certain languages. The three most commonly used ones in front end are HTML, CSS, and Javascript.**

    11. **HTML is like the framework for any website. It’s good to think of it as a house. If you are building a house, you need a foundation and a frame before you build walls and make everything pretty.**

    12. **After you use the HTML to make that framework, you can make the website pretty by using CSS.**

6. **Always Be Closing and Indentation Matters**

    13. **When you start coding today, everything is going to be done in blocks of code. Each block is self contained so you always have the same flow with creating one.**

    14. **First you open the block, then you close the block. In 4geeks,we have a saying for this "ALWAYS BE CLOSING".**

    15. **Let’s take a look at a typical block of HTML (setup a <head> <body> and <p> on the ****board****)**

    16. **Notice there is a start and end to the block. These opening and closing statements are called "Tags".**

    17. **Each statement of a code document also has it’s own indentation, so that it is more legible to any developers that review your code. This is VERY important as you always want your coding to be understandable by other people working on projects with you.**

    18. **Now, to get acquainted with this a bit, were going to try an exercise.**

    19. **(have students open and start the postcard with HTML only, no CSS)**

**Here is where they start coding the html of the postcard for 45 min, everyone should be able to finish the html before starting with the css.**

7. **CSS Selectors (basic ones)**

    20. **Now that you have created your first project in HTML, you have to consider the styling that will be needed.**

    21. **As we discussed before, the HTML provides all of the elements on the page, but the CSS is what gives those elements size, color, position, and even transitions or animations.**

    22. **Let’s talk about how CSS works.**

        1. **CSS allows you to set the properties we just mentioned (like size and color) by targeting that element.**

        2. **The targeting occurs by using something called Selectors, which are basically the syntax that you use to define what you are targeting.**

        3. **Some basic selectors are: ID, CLASS, and ELEMENT**

        4. **CSS formatting is always done as follows:  selector { property: value; }**

    23. **ID is something that you can set up on an HTML tag that UNIQUELY identifies that specific tag (whether it’s a Paragraph, Div, or Section)**

        5. **ID is a property of the element**

        6. **This is done by adding the property ‘ id="name” ’**

        7. **Example: <div id="myDiv”>**

        8. **When you have defined it in your HTML, the targeting in your CSS would be:   #myDiv**

        9. **The # (hash/octothorpe) tells the interpreter that you are targeting an ID**

    24. **Class is similar to ID that it helps to identify the specific element but it is NOT Unique**

        10. **Class is a property of the element**

        11. **An element can have many classes or not**

        12. **Similar to ID, you would add ‘ class="name” ’**

        13. **Example: <div class="row”>**

    25. **Element is just as it sounds.**

        14. **Each type of tag that you use in your HTML has an Element Selector in CSS.**

        15. **You don’t need a special property in your html for this as it’s a broad range selector, meaning that when used alone it will select all of that element type.**

        16. **An example would be div elements. To select all divs in css, you would just use the div selector:   div { some css }**

    26. **In CSS, selectors can be combined to enhance the specificity of your targeting. Rule of thumb goes that the more specific CSS will take precedence.**

