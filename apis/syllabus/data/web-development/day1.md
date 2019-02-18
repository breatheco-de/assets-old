# Day 1 - Web Development

1. Present the academy and team (3min).
2. Students present itself (5min).
3. Sign informal agreement (3min).
4. Make sure everyone is on c9 (10min).
5. Master-class about The internet, HTTP and HTML (20min).
5. Students code the postcard HTML (45min).
6. Master-class about CSS (10min).
7. Students code the postcard CSS (45min).

##### Present the academy
- 6pm Small Presentation
- Who we are: Each teacher presents itself.
- Get everyone to introduce themselves:
```
First name and last
1. What would you like to be called?
2. What do you do now? (for work)
3. What do you plan on doing when you become a developer?
```
##### Make sure everyone is on c9
- Help them get started on cloud 9, slack, etc.
- Invite them to team on c9.
- Don’t let them skip the steps on cloud 9 setup
- They have to click that they are a student and are part of a team
 
##### Master Class
 
**Client vs Server**  
Explain how for student to access Google, their website is hosted on another computer called a “Server” that they own.  
The student would have to communicate from their local computer (called a “client”), to get the website from the server so they can view and use it.

**HTTP Request vs Response**  
The method used for this communication is called HTTP, which stands for Hyper Text Transfer Protocol.  
The way that you get the website is by sending a “Request” to the server. This tells the server what website or resource you are looking to view/use. The server then responds with the information.  
The server then sends a response, which tells will confirm the receipt of the request and let your computer know that it will begin sending data. (second day content)  
This back and forth process will continue with more Request=>Response pairs until you have finished loading your remote resource. (in this case, Google – second day content)

**Everything is text!**  
With HTTP, it’s important to focus on the first ‘T’ in the acronym, which stands for ‘Text’. In this protocol, everything is sent back and forth as TEXT.  
While there are other protocols out there, for now, we want to remember that with HTTP – Everything is TEXT.

**Browser Interpretation**  
After your browser uses the HTTP protocol and receives the data as text, it then has the job of interpreting that text and converting it to the correct visual format. (What do you think that text is converted into? Open your cheat sheet for HTML and guess. Images, tables, videos, animations, etc)

**HTML vs CSS**  
So that the developers that made Google (or any other website) can create the experience you know and love, they have to use certain languages. The three most commonly used ones in front end are HTML, CSS, and Javascript.  
HTML is like the framework for any website. It’s good to think of it as a house. If you are building a house, you need a foundation and a frame before you build walls and make everything pretty.   
After you use the HTML to make that framework, you can make the website pretty by using CSS.  

**Always Be Closing and Indentation Matters**  
When you start coding today, everything is going to be done in blocks of code. Each block is self contained so you always have the same flow with creating one.  
First you open the block, then you close the block. In 4geeks,we have a saying for this `“ALWAYS BE CLOSING”`.   
Let’s take a look at a typical block of HTML (setup a `<head>` `<body>` and `<p>` on the board)  
Notice there is a start and end to the block. These opening and closing statements are called **“Tags”**.  
Each statement of a code document also has it’s own indentation, so that it is more legible to any developers that review your code. This is VERY important as you always want your coding to be understandable by other people working on projects with you.  
Now, to get acquainted with this a bit, were going to try an exercise.  

> Have students open and start the postcard with HTML only, no CSS) 
>
> Here is where they start coding the html of the postcard for 45 min, everyone should be able to finish the html before starting with the css.
 
 
**CSS Selectors (basic ones)**
Now that you have created your first project in HTML, you have to consider the styling that will be needed.  
As we discussed before, the HTML provides all of the elements on the page, but the CSS is what gives those elements size, color, position, and even transitions or animations.  

**Let’s talk about how CSS works.**  
CSS allows you to set the properties we just mentioned (like size and color) by targeting that element.  
The targeting occurs by using something called Selectors, which are basically the syntax that you use to define what you are targeting.  
```
Some basic selectors are: ID, CLASS, and ELEMENT  
```
CSS formatting is always done as follows:
```selector { property: value; }```
`ID` is something that you can set up on an HTML tag that UNIQUELY identifies that specific tag (whether it’s a Paragraph, Div, or Section)
`ID` is a property of the element
This is done by adding the property `id=”name”` for example:
```html
<div id=”myDiv”>
```
When you have defined it in your HTML, the targeting in your CSS would be: `#myDiv`  
The `#` (hash/octothorpe) tells the interpreter that you are targeting an `ID`  
Class is similar to ID, in that, it helps to identify the specific element but it is NOT Unique  

**Class is a property of the element**  
An element can have many classes or not  
Similar to ID, you would add `class=”name”`, for example: 
```html
<div class=”row”>
```
Element is just as it sounds.  
Each type of tag that you use in your HTML has an Element Selector in CSS.  
You don’t need a special property in your html for this as it’s a broad range selector, meaning that when used alone it will select all of that element type.  
An example would be div elements. To select all divs in css, you would just use the div selector:
```css
div { 
    //some css rule here
}  
```
In CSS, selectors can be combined to enhance the specificity of your targeting. Rule of thumb goes that the more specific CSS will take precedence.  
