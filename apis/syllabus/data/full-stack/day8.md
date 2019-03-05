**Day 8 - Arrays, Loops and Conditionals**

**Welcome class.**

**Today’s lesson will be on Javascript. Now we are going to start your serious journey into coding.**

**Install C9-scripts with students**


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
