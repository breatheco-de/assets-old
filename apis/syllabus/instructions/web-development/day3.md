# Day 3 - Web Development

Welcome everyone, see how the weekend went. 
  * How many got to code saturday at the downtown campus? 
  * How many worked on the repl’its? 
  * Did everyone finish the 2 projects? (postcard and simple instagram)
 
Today we are going to review Bootstrap which is going to completely simplify your life as a developer.  
***(Total time: 20 min-25min)***
 
1. Bootstrap (open the breatheco.de lesson on Bootstrap - day 3)
  * So what is Bootstrap? We touched on this a bit last class, but Bootstrap is a framework that was developed to simplify front end design/development.
  * It has tons of components to simplify the process of styling your elements. 
  * Each component is built around classes that they have constructed, which you will include in your html projects.
  * The biggest take away will be that with Bootstrap, you will have most of the baseline css work done for you and all that will be left is tweaking elements to your unique needs. This removes the heavy lifting in CSS.
    * Let’s take a look at their website really quick and see what I mean. (open the bootstrap website)
2. Components
  * Navbar
    * this is an example of a really useful component that will be in almost every project you create.
    * every site needs a navigation, and Navbar allows you to create either simple or complex navigation effortlessly.
  * Card
    * Ask students: If you think about your instagram project, What can you visualize having represented as a card?
      * Answer: Each of the picture/text combos are perfect representations of a card. 
    * Cards are really easy ways to represent data in a nice visual format by combining a picture and text.
  * Modal
    * This element isn’t purely css. It will require Javascript to make it work.
    * Modals are kind of like pop-ups in that they overlay all elements on the page and display some data. They have many uses and can give a super polished look to a project when used correctly.
    * Let’s look at an example: https://getbootstrap.com/docs/4.0/components/modal/#live-demo 
3. Here is your basic Workflow with Bootstrap Components:  (assuming you have your design already planned)
  * Identify the components you will need.
    * visit the site, search through components and find what you will need. 
  * Copy & Paste the element into your code in the specific area it is needed
    * For example, if you have a Navbar, it will typically be the top of your page. 
    * So within the body, but either in a section or div that you created to house your header is where you will paste the code. 
  * and finally customize the component for your specific design
    * This is where the fun stuff comes in.
    * Bootstrap takes the guesswork out of the beginning and allows you to “bootstrap” or spin up a project quickly.
    * However, once you have the project created, you need to customize each component using CSS to make it unique to your specific design. 
      * Adjust colors
      * maybe decide between rounded or rectangle buttons
      * Possibly slight adjustments to form elements, etc.
    * This is where you will use all of the awesome CSS skills you have been practicing.
4. Helper/Utility Classes that come with bootstrap
  * So, in addition to the components, bootstrap also adds helper classes.
  * These are classes you can attach that do things such as control margin or padding, control borders, or even float elements.
  * For example, if you wanted no margin on the left of the element, you can add the class ```ml-0``` to the element and it will set the left margin to zero.
5. Now that you understand how these components and helper classes work and what they do for your projects, it’s time to review the most important part of what bootstrap does for you.
  * Layout using Grid system
    1. Explain 12 column layout and how it works
        * every line is a Row
        * this row holds columns
        * every Row on a page has 12 columns
        * each column has its own spacing between (gutters)
        * columns are equally measured in percentages so that they are responsive.
    2. When you build layouts, you specify how the columns will react at each screen size.
        * the sizes are determined by breakpoints which are defined in bootstrap (you can read these on the documentation)
        * the basic sizes are: col, col-xs, col-sm, col-md, col-lg, col-xl
        * To see these breakpoints and their corresponding sizes, check out the bootstrap layout documentation and scroll to the section on “grid”
6. One more thing I want to touch on is Fonts.
  * Bootstrap doesn’t set font size on HTML (which is the common practice for the documents base size)
  * It assumes a base font size of 16px (which is the browser standard) and specifies a starting value of 1rem on the body.
  * This allows all fonts to scale up relative to that size. It is recommended in responsive design to use rem units for fonts because the scale in respect to the base document font
  * You will want to familiarize yourself with measurement differences between EM, REM, and PX.
    * PX = pixel measure and is a standard measure used on base sizes (doesn’t not change with resize of browser; fixed size)
    * EM = is a measurement that is relevant to the parent (Ex. parent font = 20px, child font = 1.5em = 30px)
    * REM = is the same as EM, except it’s relative to the base document font (set on html in your css). As mentioned earlier, if you don’t override this, the base font is usually 16px.
 
Have class work on bootstrap instagram.

Answer any questions. Remind them this project is due by Friday.
