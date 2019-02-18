
**Day 11 -  React.js (cont’d)**

**Welcome class. Take any questions about React (10 min)**

**Review the landing page project as a class.**

**Review Functional Component vs Class Component.**

1. **Conditional Rendering**

    1. **As we discussed last class, you control what is rendered in react.**

    2. **This means that you can use conditional logic to decide what is rendered. (If..else, For, etc.)**

    3. **You can create loops to test for a condition and display an item if the condition is or is not met**

2. **The component state**

    4. **The state is an object in the constructor that allows you to store the values that define the state/condition of anything at a given moment.**

    5. **For example, you can store something like a userID and whether the user is logged in or not.**

    6. **You can also store an object that was retrieved from the database or just about any other value.**

    7. **Think of it as temporary cache memory for the component.**

    8. **The state is always within the constructor, and before you call state you have to call super();**

    9. **Super calls the constructor function of the current component’s parent and will give you access to the parent component’s props.**

3. **The state is immutable**

    10. **Last class we mentioned that the state is immutable, this means that it cannot be modified/edited/updated directly.**

    11. **To change the state, you have to use a special function called setState() as follows:**

        1. **this.setState({**
  **property: value,**
  **property: value**
**});**

4. **Using const, map, filter and concat to prevent state mutation**

    12. **The process of actually changing a variable's value over time, within a single context, is called** **mutation** **.**

    13. **Let’s discuss a few functions that can help you prevent state mutation**

    14. **const -  behaves like let, except it helps you to assign/declare a constant variable. This is good for storing things like PI or values that won't change.**

    15. **map - helps you to iterate over the values of an array.**

        2. **similar to forEach, except that**

            1. **map doesn’t modify the original array, it creates a new array to work with.**

            2. **This helps your original array to remain unchanged.**

            3. **Format: numbers.map(Math.sqrt)**

            4. **notice that in the parenthesis is a function to run on each item.**

    16. **filter - allows you to create an array filled with all array elements that pass a test (provided as a function)**

        3. **Instead of modifying your initial array, your new dataset is in a new array that you can work with further.**

        4. **Format: ages.filter(checkAdult);**

    17. **concat() - the concat method allows you to concatenate the values of two or more arrays**

        5. **This is performed by joining them into a new array**

        6. **The original arrays are not modified**

        7. **Format:  ****_array1_****.concat(****_array2_****,****_ array3_****, ...,****_ arrayX_****)**

    18. As a real developer, you will be working in an immutable way. You don’t want to modify the original data by changing it. You want to keep it immutable and work with a new array.

    19. Get in the habit of using these functions to manipulate your data

    20. In react, array manipulation like push, pop, slice don’t work. So you have to use concat(), map, filter.

    21. You don’t want to change the original arrays that were already assigned to the state

    22. Instead, you will generate new arrays and re-set the state with those new ones

5. **Constructor**

    23. **[https://breatheco.de/en/lesson/react-js/#the-component-constructor]**(https://breatheco.de/en/lesson/react-js/#the-component-constructor)

    24. **When we started JS , we talked about how you should initialize your values always.**

    25. **This is no different for the state and constructor.**

    26. **Since the constructor is created at the modules runtime, you should first init your state variables, this way your code runs smoothly and you don’t have extra errors.**

6. **Lifecycle Methods**

    27. **[https://breatheco.de/en/lesson/react-js/#component-lifecycle]**(https://breatheco.de/en/lesson/react-js/#component-lifecycle)

    28. **As we discussed briefly, lifecycle methods are methods that allow you to execute code at points in your component’s runtime.**

    29. **let’s go over the diagram to understand a bit better**

7. **Props**

    30. **I prepared an example of props and how they can pass down from parent to child.**

    31. **Let’s look at the landing page we did last class**

        8. **the components we design can always be done static, but what fun would that be**

        9. **We always want to work in an event driven fashion**

        10. **In practical, real world applications, we will want to pass data back and forth through the application or site.**

        11. **This will require props and state.**

        12. **Showcase example**

**Now, for assignments there are a few. 2 easy ones to get your teeth wet and one that we will be doing till the end of next class.**

1. Simple Counter - [https://projects.breatheco.de/d/simple-counter-react#readme](https://projects.breatheco.de/d/simple-counter-react#readme) 

    1. cover strategy

2. Traffic Light - [https://projects.breatheco.de/d/traffic-light-react#readme](https://projects.breatheco.de/d/traffic-light-react#readme) 

    2. cover strategy

**After you have finished those two, You can move on to the 3rd:**

1. React ToDo list - [https://projects.breatheco.de/d/todo-list#readme](https://projects.breatheco.de/d/todo-list#readme) 

    1. cover strategy

**Next class we will go deeper into react and finish out the ToDo List. Please do the reading and repl’its.**