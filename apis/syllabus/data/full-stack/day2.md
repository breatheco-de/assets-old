**Day 2 - CSS, DRY, and Box Model**

**Welcome everyone and ask how many got around to the reading.**

**Make sure everyone has access to BreatheCode platform and also to the replit exercises.**

**Get feedback about repl’it from those using it already.**

**Quick Recap of previous day with additional points about Request/Response. (10-15 min)**

***Mention that when they get to the repl’it for HTML tables and CSS, they will see how some elements have properties for color and border, which is how the web was styled before CSS.***

**Lecture (20min)**

1. **Do not use ID as CSS selectors (use specificity)**

    1. **As we discussed previously, CSS utilizes Specificity for targeting.**

    2. **When we talked about ID and Class selectors, I mentioned that in most cases it’s better to use classes because they are reusable.**

    3. **Try to avoid ID selectors unless you absolutely need them. It’s possible to achieve the same effect as an ID selector by using specificity.**

    4. **If you google "css specificity calculator", you can find tools that help you to calculate specificity of your selectors, but you shouldn’t need them if you understand the basic rules.**

    5. **Review how specificity works.**

        1. **Combine selectors to get more specific about which element you are styling**

        2. **w3schools breaks down specificity hierarchy in this order (from greatest to least specific):**

            1. **Inline styles - An inline style is attached directly to the element to be styled. Example:** `<h1 style="color: #ffffff;">.`

            2. **IDs - An ID is a unique identifier for the page elements, such as #navbar.**

            3. **Classes, attributes and pseudo-classes - This category includes .classes, [attributes] and pseudo-classes such as :hover, :focus etc.**

            4. **Elements and pseudo-elements - This category includes element names and pseudo-elements, such as h1, div, :before and :after.**

        3. **You can read more about specificity on breathco.de**

2. **Before & ::After Selectors**

    6. **Help to insert content or styling before or after an element**

    7. **This is really helpful as it allows you to do cool things like create interesting block quote effects. You can add giant quotation marks or symbols.**

3. **DRY Technique**

    8. **Ask if anyone remembers what we said DRY Programming means?**

        4. **Don’t repeat yourself**

        5. **Everything must have a single representation, don’t repeat elements unnecessarily. (example: reusable classes instead of same property repeated over and over)**

        6. **A good example of this would be, if I was going to apply a color blue to 5 parts of the site, I could add the color: blue property to every area of my CSS, or the better practice is either:**

            5. **Create a class that will apply the blue color and attach it to the items that need it.**

            6. **Create a multi-part css statement. Multiple selectors that are comma separated to target several specific elements**

                1. **Example: .myHeader, .myFooter, .contentDiv { color: blue; }**

                2. **This is the best of the two and you should get use to it as it will save you tons of lines of code and make your code way more legible.**

    9. **Conversely, you may think it’s funny that they call the opposite of DRY -  WET programming, which they have given the following titles to:  "write everything twice", "we enjoy typing" or "waste everyone's time"**

4. **Box Model**

    10. **In our first class, we touched on this a bit, but didn’t give it a formal title.**

    11. **Box layout means that all elements can be thought of as boxes. In our first assignment, we thought of all divs as content boxes that held other things in them (like russian nesting dolls)**

    12. **But each element has a box too**

    13. **There are 4 main parts to the typical box model: (draw the following on the board or show picture from breathcode)**

        7. **Content - this is what is inside the box**

        8. **Padding - this is spacing between the border and content (inside box also)**

        9. **Border - this is the edge of the box**

        10. **Margin - this is spacing between the outside of the box and the next element over in any direction. (which could be the edge of the page, another div, a paragraph, or even an image. )**

    14. **You can also have a background color or image, this will show in the content area of your box.**

    15. **Additionally, you have properties that are important to your elements such as: width and height, display, position, etc.**

        11. **Width and height - talk about the dimensions of the box**

        12. **display - talks about whether an element is visible or not, also refers to how it can be positioned in relation to other objects**

            7. **block - sits on it’s own line (takes up whole width)**

            8. **inline - can sit next to other elements (works like a span)**

            9. **there are tons of others so you will have to read up to understand them all, but those are the two main ones**

    16. **I recommend going over the CSS and HTML cheat sheets that we have available on the breathco.de platform under Assets.**

**Have students start next project - Simple Instagram (from day 2)**

**Explain that all projects will have to be delivered by next week using the breathecode ** **platform.**

**Answer any questions.**

**At end of class, remind them to finish the the postcard with CSS and simple instagram for Monday.**

**Encourage them to use the Chat or to DM me on slack. Work on the Reading ahead of each class and do the Repl’its, THEY HELP!**
