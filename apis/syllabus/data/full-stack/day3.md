**Day 3 - Bootstrap 4**

**Welcome everyone, see how the weekend went.**

* **How many got to code saturday at the downtown campus?**

* **How many worked on the repl’its?**

* **Did everyone finish the 2 projects?**

**Today we are going to review Bootstrap which is going to completely simplify your life as a developer.  (Total time: 15min)**

1. **Bootstrap**

    1. **So what is Bootstrap? We touched on this a bit last class, but Bootstrap is a framework that was developed to simplify front end design/development.**

    2. **It has tons of components** **to simplify the process of styling your elements.**

    3. **Each component is built around classes that they have constructed, which you will include in your html projects.**

    4. **The biggest take away will be that with Bootstrap, you will have most of the baseline css work done for you and all that will be left is tweaking elements to your unique needs. This removes the heavy lifting in CSS.**

    5. **Let’s take a look at their website really quick and see what I mean.**

2. **Components**

    6. **Navbar**

        1. **this is an example of a really useful component that will be in almost every project you create.**

        2. **every site needs a navigation, and Navbar allows you to create either simple or complex navigation effortlessly.**

    7. **Card**

        3. **If you think about your instagram project, What can you visualize having represented as a card?**

        4. **Cards are really easy ways to represent data in a nice visual format by combining a picture and text.**

    8. **Modal**

        5. **This element isn’t purely css. It will require Javascript to make it work.**

        6. **Modals are kind of like pop-ups in that they overlay all elements on the page and display some data. They have many uses and can give a super polished look to a project when used correctly.**

        7. **Let’s look at an example:** **[https://getbootstrap.com/docs/4.0/components/modal/#live-demo]**(https://getbootstrap.com/docs/4.0/components/modal/#live-demo)

3. **Here is your basic Workflow with Bootstrap Components:  (assuming you have your design already planned)**

    1. **Identify the components you will need.**

        1. **visit the site, search through components and find what you will need.**

    2.  **Copy & Paste the element into your code in the specific area it is needed**

        1. **For example, if you have a Navbar, it will typically be the top of your page.**

        2.  **So within the body, but either in a section or div that you created to house your header is where you will paste the code.**

    3.  **and finally customize the component for your specific design**

        1.  **This is where the fun stuff comes in.**

        2.  **Bootstrap takes the guesswork out of the beginning and allows you to "bootstrap" or spin up a project quickly.**

        3.  **However, once you have the project created, you need to customize each component using CSS to make it unique to your specific design.**

            1. **Adjust colors**

            2. **maybe decide between rounded or rectangle buttons**

            3. **Possibly slight adjustments to form elements, etc.**

        4.  **This is where you will use all of the awesome CSS skills you have been practicing.**

4. **Helper/Utility Classes that come with bootstrap**

    1.  **So, in addition to the components, bootstrap also adds helper classes.**

        1.  **These are classes you can attach that do things such as control margin or padding, control borders, or even float elements.**

        2.  **For example, if you wanted no margin on the left of the element, you can add the class ml-0 to the element and it will set the left margin to zero.**

5. **Now that you understand how these components and helper classes work and what they do for your projects, it’s time to review the most important part of what bootstrap does for you.**

    1.  **Layout using Grid system**

        1.  **Explain 12 column layout and how it works**

            1. **every line is a Row**

                1. **this row holds columns**

            2. **every Row on a page has 12 columns**

                1. **each column has its own spacing between (gutters)**

                2. **columns are equally measured in percentages so they are responsive.**

        2.  **When you build layouts, you specify how the columns will react at each screen size.**

            1. **the sizes are determined by breakpoints which are defined in bootstrap (you can read these on the documentation)**

            2. **the basic sizes are: col, col-xs, col-sm, col-md, col-lg, col-xl**

            3. **To see these breakpoints and their corresponding sizes, check out the bootstrap layout documentation and scroll to the section on "grid"**

6. **One more thing I want to touch on is Fonts.**

    1.  **Bootstrap doesn’t set font size on HTML (which is the common practice for the documents base size)**

    2.  **It assumes a base font size of 16px (which is the browser standard) and specifies a starting value of 1rem on the body.**

    3.  **This allows all fonts to scale up relative to that size. It is recommended in responsive design to use rem units for fonts because the scale in respect to the base document font**

    4.  **You will want to familiarize yourself with measurement differences between EM, REM, and PX.**

        1.  **PX = pixel measure and is a standard measure used on base sizes (doesn’t not change with resize of browser; fixed size)**

        2.  **EM = is a measurement that is relevant to the parent (Ex. parent font = 20px, child font = 1.5em = 30px)**

        3.  **REM = is the same as EM, except it’s relative to the base document font (set on html in your css). As mentioned earlier, if you don’t override this, the base font is usually 16px.**

**Have class work on bootstrap instagram.**

**Answer any questions. Remind them this project is due by Friday.**