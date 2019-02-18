**Day 7 - Javascript Variables, DataTypes and Arrays**

**Welcome class.**

**Today’s lesson will be on Javascript. Now we are going to start your serious journey into coding.**

**Install C9-scripts with students**

1. **Variables**

    1. **Variables are javascript’s version of containers that hold some information.**

    2. **An example of this is:**

        1. **Let’s say we needed to store someone’s birthday for an age verification script**

        2. **One of our variables might be:**

            1. **var day = 29;**

        3. **There are three things going on here.**

            2. **First, we are creating a variable called day.**

            3. **Secondly, we are storing a value of 29 in that variable.**

            4. **Finally, notice the syntax. All javascript lines typically end with semicolon. There will be some cases where you don’t use semicolon and we will learn those later but for now, all lines are with semicolon.**

    3. **Given that we defined the var above, var day = 29;**

        4. **if we want to display it for testing, we can show this value in the javascript console**

            5. **explain how to open console**

            6. **so to display this, you would use:  console.log(day);**

            7. **will return value of 29**

2. **Data Types**

    4. **Javascript is not a strictly typed system.**

        5. **With strictly typed programming languages, when we define a variable, we have to specify it’s type.**

        6. **Do exercise with class**

          * **Boxes exercise - Teacher must have a few different basic containers (3 boxes - each with a different color on them (red, blue, green); 1 cup and some water; different pieces of paper that have a number, a name and a true/false on it - 3pcs of paper total) **
          * **First use the cup and explain, if we have liquid (pour water into the cup), we can only store it in a container that can hold liquid - like a cup **
          * **Second, pass out the papers. Then take the boxes and explain that:**
            * **Red boxes can only hold true or false paper**
            * **Blue boxes can only hold strings/names**
            * **Green boxes can only hold numbers**

          * **Students should come up and put their paper in the correct box, but as they come up, teacher makes sure it's the correct box and stops them from making a mistake**
          * **The teacher is validating the data type**
            * **Teacher should make sure to tell the students that he is running a "typeof" on the variable**
            * **If the container type matches the data type, the data can be stored in the container/variable**

    5. **Type specifies the kind of information that can be stored in the variable, such as:**

        6. **Boolean - true/false**

        7. **string - text strings**

        8. **number - any type of number -  integer numbers, negative numbers, decimal numbers, floats, etc.**

        9. **Undefined - nothing was set or specified for the variable. so the variable has no value**

        10. **Null - When a database or function returns nothing, it returns null**

        11. **array - data sets [2,3,4,"cat”,242,”d”]**

        12. **object - ***var*** car = {type:****"Fiat"****, model:****"500"****, color:****"white"****};**

    6. **Data Types on Breatheco.de:**  **[https://breatheco.de/en/lesson/learn-to-code-js/#datatypes]**(https://breatheco.de/en/lesson/learn-to-code-js/#datatypes)

3. **Arrays**

    7. **Since we just learned what an array is, let’s look at some basic properties of an array**

        13. **value - The value stored**

        14. **index - the position of the given value in the array (note: the first position starts at 0)**

        15. **length - the total length of the array**

    8. **Declaring an array -** **[https://breatheco.de/en/lesson/working-with-arrays/#declaration]**(https://breatheco.de/en/lesson/working-with-arrays/#declaration)

        16. **To initialize an array , you can use the following:**

            8. **var myArray = [];**

            9. **This defines the array as an empty set so that you can add values later.**

            10. **don’t use:    var myArray = new Array(1,2,3,4,5);**

                1. **There is some funky behavior that happens when searching for values in the array.**

                2. **To learn more about this, click the link in the note on the lesson and read the article.**

        17. **You can also initialize an array with a value in it**

            11. **var myArray = [1,2,3,4,5];**

    9. **Displaying the value of an array**

        18. **Using console, we can show it as ***console***.****log****(****myArray****[****0****]);**

        19. **For the array, var myArray = [1,2,3,4,5];  what is myArray[1]?**

    10. **Updating a specific position**

        20. **myArray[4]=3;**

    11. **Adding elements to an array**

        21. **Push function adds a new element to the end of the array**

        22. **Syntax:   myArray.push(‘Chris’);   will add Chris to the end**

    12. **Adding/Removing from the start of an array**

        23. **shift - removes the first item in the array**

            12. **Syntax:    myArray.shift();**

        24. **unshift - adds item(s) to the start**

            13. **Syntax:   myArray.unshift(‘bob’,’chris’);**

    13. **Looping**

        25. **For Loop**

            14. **for (i = 0; i < myArray.length; i++)**

            15. **first part is initializing start value**

            16. **second part sets a condition to run/test each loop**

            17. **last part increments the loop counter by 1**

        26. **For… In…**

            18. **for (var index in myArray)**

            19. **index in this case is the variable that we are storing the information in the array as we loop through**

            20. **myArray is the array we pull from**

    14. **Removing from array - ** **[https://breatheco.de/en/lesson/working-with-arrays/#removing-from-an-array]**(https://breatheco.de/en/lesson/working-with-arrays/#removing-from-an-array)

        27. **Slice - deletes everything but the segment you selected**

            21. **returns it as a new array**

        28. **Splice - removes or adds elements at a given position**

            22. **changes the original array**

    15. **Sorting arrays**

        29. **Sort - sorts in alphabetical order**

            23. **fruits.sort();**

        30. **Reverse -sorts in reverse alphabetical order**

            24. **fruits.reverse();**

    16. **Sorting numbers**

        31. **You have to use a comparison function to take care of the comparison**

    17. **Sorting objects**

        32. **Again, using the comparison function, you can compare specific elements within an object**

4. **Functions (anonymous vs normal)**

    18. **Functions take something and do something to it.**

    19. **[https://breatheco.de/en/lesson/learn-to-code-js/#functions]**(https://breatheco.de/en/lesson/learn-to-code-js/#functions)

    20. **They also allow you to keep your code DRY by creating reusable code blocks.**

    21. **Another thing is that functions allow you to break a bigger problem into a few smaller ones so that you can tackle it more effectively.**

    22. **Two types of functions:**

        33. **Normal function**

            25. **function myFunction** **(a,b)** **{**
	**return a\*b;**
**}**

            26.  takes input,returns a value

            27. The function itself isn’t executable, only the operation within it is. This is why the function isn’t ended with a semicolon, but the operation is.

        34. Sometimes, you want to set a variable and have it return a function. In this case, the function isn’t named and only used at one point.

            28. This is called an Anonymous function

            29. var x = function (a,b) {return a*b};

5. **The forEach**

    23. **Let’s go over basic loops first:**

        35. **If .. else**

        36. **Switch**

        37. **While**

        38. **For**

        39. **For… in**

        40. **forEach**

            30. **This iterates over the array to perform a function on it.**

            31. **array.forEach(function(currentValue, index, array object), thisValue)**

6. **Every javascript code starts OnLoad**

    24. **Basic timeline to loading of a document :     Pre-load | Load | Render**

    25. **When you declare your javascript you typically do it onLoad**

7. **String Concatenation**

    26. **"blah"+”blah”= “blahblah”**

    27. **var str1 = "Hello";**
**var str2 = "world!";**
**var res = str1.concat(str2);**

**Let’s work on the Excuse Generator**

**Cover the project and what it entails**

**Show how to use the Breathcode CLI and Vanilla JS boilerplate**

**Let them start working on assignment**
